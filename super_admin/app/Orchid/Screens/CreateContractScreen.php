<?php

namespace App\Orchid\Screens;

use App\Models\Contract;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateContractScreen extends Screen
{
    public $requiredFields = ['title', 'url', 'state_province', 'description',];
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
        return 'Add a New Contract';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createContract'),
            ModalToggle::make('Mass Import Contracts')
                ->modal('massImportModal')
                ->method('massImportContracts')
                ->icon('plus'),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.contract.list'),
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
            Layout::modal('massImportModal', [
                Layout::rows([
                    Input::make('contract_csv')
                        ->type('file')
                        ->title('File must be in csv format. Ex. contracts.csv')
                        ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the contracts: <br>
                        • title <br>
                        • url <br>
                        • state_province <br>
                        • description <br>'),
                    Link::make('Download Sample CSV')
                        ->icon('download')
                        ->href('/sample_contracts_upload.csv'),
                ])
            ])
            ->title('Mass Import Contracts')
            ->applyButton('Import')
            ->withoutCloseButton(),

            Layout::rows([
                Input::make('title')
                    ->title('Title')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Hot Dogs Contract'),
                Input::make('url')
                    ->title('URL')
                    ->type('url')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. https://hotdogs.com/contract'),
                Input::make('state_province')
                    ->title('State/Province')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Arkansas'),
                TextArea::make('description')
                    ->title('Description')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. This is a contract...')
                    ->rows(12),
            ]),
        ];
    }

    /**
     * Add a single contract.
     */
    public function createContract(Request $request) {
        try {
            $contractFields = $request->validate([
                'title' => 'required|max:255|unique:contracts',
                'url' => 'required|max:255|url',
                'state_province' => 'required|max:255',
                'description' => 'required|max:65535',
            ]);
            $contractFields['user_id'] = Auth::id();
            Contract::create($contractFields);
            Toast::success('Contract added succesfully!');
            return redirect()->route('platform.contract.list');
        } catch (Exception $e) {
            Alert::error('There was an error adding the contract. Error code: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Mass add contracts by importing a CSV file.
     */
    public function massImportContracts(Request $request) {
        try {
            $contracts = $this->csvToArray($this->getValidFilePath($request));
            $keys = array_keys($contracts[0]);
            foreach($this->requiredFields as $field) {
                if (!in_array($field, $keys)) {
                    throw ValidationException::withMessages(['"' . $field . '"' . ' is missing in your csv file.']);
                }
            }
            $user_id = Auth::id();
            foreach($contracts as $contract) {
                $contractData = [
                    'title' => $contract['title'],
                    'url' => $contract['url'],
                    'state_province' => $contract['state_province'],
                    'description' => $contract['description'],
                ];
                $validatedData = Validator::make($contractData, [
                    'title' => 'required|max:255|unique:contracts',
                    'url' => 'required|max:255|url',
                    'state_province' => 'required|max:255',
                    'description' => 'required|max:65535',
                ])->validate();
                $validatedData['user_id'] = $user_id;
                $validatedData['created_at'] = new DateTime;
                $contractsData[] = $validatedData;
            }
            DB::table('contracts')->insert($contractsData);
            Toast::success('Contracts imported succesfully!');
            return redirect()->route('platform.contract.list');
        } catch (Exception $e) {
            Alert::error('There was an error importing the contracts. Error Code: ' . $e->getMessage());
        }
    }

    /**
     * Get the imported CSV file's path from which to mass add contracts, making sure that it exists and has the correct file extension.
     */
    public function getValidFilePath(Request $request) {
        if (!is_null($file = $request->file('contract_csv'))) {
            if (($path = $file->getRealPath()) !== false) {
                $extension = $file->extension();
                if ($extension != 'csv' and $extension != 'txt') {
                    throw ValidationException::withMessages(['Incorrect file type.']);
                } else {
                    return $path;
                }
            } else {
                throw ValidationException::withMessages(['The file could not be found.']);
            }
        } else {
            throw ValidationException::withMessages(['Upload a csv file to import contracts.']);
        }
    }

    /**
     * Get an array from a CSV file.
     */
    public function csvToArray($filename='', $delimiter=',') {
        if (!is_readable($filename)) {
            throw ValidationException::withMessages(['The file could not be read.']);
        }
        if (($file = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($file, separator: $delimiter)) !== false) {
                if (!isset($header)) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($file);
        }
        return $data;
    }
}
