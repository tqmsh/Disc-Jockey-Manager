<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class BulkUploadDressScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bulk Upload Dresses';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Back to Dress List')
                ->icon('arrow-left')
                ->method('goBack'),
            Button::make('Process File')
                ->type(Color::PRIMARY())
                ->method('saveDress')
                ->icon('check'),
            Link::make('Download Sample CSV')
                ->type(Color::INFO())
                ->icon('download')
                ->href('/sample_dresses_upload.csv'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('upload')
                    ->type('file')
                    ->title('Upload CSV'),
                CheckBox::make('ignore_duplicates')
                    ->placeholder('Ignore duplicate entries')
                    ->sendTrueOrFalse(),
            ]),
        ];
    }


    public function saveDress(Request $request)
    {
        $request->validate([
            'upload' => 'required|mimes:csv,txt|max:2048',
            'ignore_duplicates' => 'required|boolean',
        ]);

        $file = $request->file('upload');
        $ignoreDuplicates = $request->input('ignore_duplicates');
        $csvData = array_map('str_getcsv', file($file->getPathName()));
        $dataWithoutHeader = array_slice($csvData, 1);

        $validRows = [];
        $modelNumbers = [];

        foreach ($dataWithoutHeader as $row) {
            $row = array_pad($row, 7, null);
            $row = array_map(function ($item) {
                return $item === "" ? null : trim($item);
            }, $row);

            $validator = Validator::make($row, [
                0 => 'required|max:255',
                1 => 'required|max:255',
                2 => 'nullable',
                3 => 'nullable',
                4 => 'nullable',
                5 => 'nullable',
                6 => 'nullable|url',
            ]);

            if ($validator->fails()) {
                Alert::error('The file contains invalid data.');
                return;
            }

            $preparedRow = [
                'user_id' => Auth::id(),
                'model_name' => $row[0],
                'model_number' => $row[1],
                'description' => $row[2],
                'colours' => Dress::splitAndTrimNonEmpty($row[3], ';'),
                'sizes' => Dress::splitAndTrimNonEmpty($row[4], ';'),
                'images' => Dress::splitAndTrimNonEmpty($row[5], ';'),
                'url' => $row[6],
            ];

            // Check if the model number already exists in the CSV file
            if (isset($modelNumbers[$preparedRow['model_number']])) {
                if (!$ignoreDuplicates) {
                    Alert::error('The file contains entries with duplicate model numbers.');
                    return;
                } else {
                    // If ignoreDuplicates is set, skip this iteration and move to the next row
                    continue;
                }
            }

            $modelNumbers[$preparedRow['model_number']] = true;

            $exists = Dress::where('user_id', $preparedRow['user_id'])
                ->where('model_number', $preparedRow['model_number'])
                ->exists();

            if (!$exists) {
                $validRows[] = $preparedRow;
            } else if (!$ignoreDuplicates) {
                Alert::warning('Some dresses in the file already exist.');
                return;
            }
        }

        foreach ($validRows as $row) {
            Dress::create($row);
        }

        Alert::success(str(count($validRows)) . ' dress(es) created.');
    }

    public function goBack()
    {
        return redirect()->route('platform.dresses');
    }
}
