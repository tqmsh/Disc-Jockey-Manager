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
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class BulkUploadDressScreen extends Screen
{
    public string $name = "Bulk Upload Dresses";
    public ?string $description = "Bulk upload dresses from a CSV file.";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Back to Dress List')
                ->icon('arrow-left')
                ->route('platform.dresses'),
            Button::make('Process File')
                ->type(Color::PRIMARY())
                ->method('processCSVAndSaveDress')
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
     * @return array
     */
    public function layout(): array
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

    /**
     * Process the uploaded CSV file to save new Dress entries.
     *
     * @param Request $request
     */
    public function processCSVAndSaveDress(Request $request)
    {
        // Validate request data
        $request->validate([
            'upload' => 'required|mimes:csv,txt|max:2048',
            'ignore_duplicates' => 'required|boolean',
        ]);

        // Retrieve file and 'ignore_duplicates' value from the request
        $file = $request->file('upload');
        $ignoreDuplicates = $request->input('ignore_duplicates');

        // Parse CSV data and ignore header row
        $csvData = array_map('str_getcsv', file($file->getPathName()));
        $dataWithoutHeader = array_slice($csvData, 1);

        // Initialize empty arrays to store valid rows and model numbers
        $validRows = [];
        $modelNumbers = [];

        // Get the count of rows
        $rowCount = count($dataWithoutHeader);

        // Iterate over each row in the CSV file using a for loop
        for ($i = 0; $i < $rowCount; $i++) {
            // Get current row
            $row = $dataWithoutHeader[$i];

            // Clean and pad data
            $row = array_pad($row, 7, null);
            $row = array_map(function ($item) {
                return $item === "" ? null : trim($item);
            }, $row);

            // Validate row data
            $validator = Validator::make($row, [
                0 => 'required|max:255',
                1 => 'required|max:255',
                2 => 'nullable',
                3 => 'nullable',
                4 => 'nullable',
                5 => 'nullable',
                6 => 'nullable|url',
            ]);

            // If validation fails, display an error message including the row number and return
            if ($validator->fails()) {
                // Add 2 to the index because the CSV header row is not included and indices are zero-based
                Toast::error('Invalid data at row ' . ($i + 2) . '.');
                return;
            }

            // Prepare data for new Dress
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
                    // If ignoreDuplicates is not set, display an error and return
                    Toast::error('The file contains entries with duplicate model numbers.');
                    return;
                } else {
                    // If ignoreDuplicates is set, skip this iteration and move to the next row
                    continue;
                }
            }

            // Add model number to modelNumbers array
            $modelNumbers[$preparedRow['model_number']] = true;

            // Check if the Dress already exists in the database
            $exists = Dress::where('user_id', $preparedRow['user_id'])
                ->where('model_number', $preparedRow['model_number'])
                ->exists();

            // If the Dress does not exist in the database or ignoreDuplicates is set, add it to the validRows array
            if (!$exists) {
                $validRows[] = $preparedRow;
            } else if (!$ignoreDuplicates) {
                // If ignoreDuplicates is not set and the Dress exists in the database, display a warning and return
                Toast::warning('Some dresses in the file already exist in the database.');
                return;
            }
        }

        // Iterate over the valid rows and create a new Dress for each
        foreach ($validRows as $row) {
            Dress::create($row);
        }

        // Display a success message with the number of Dresses created
        Toast::success(str(count($validRows)) . ' dress(es) created.');
    }
}
