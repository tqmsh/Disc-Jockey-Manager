<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\School;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;

class CreateSchoolScreen extends Screen
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
        return 'Add a New School';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            ModalToggle::make('Mass Import Schools')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),

            Button::make('Add')
                ->icon('plus')
                ->method('createSchool'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.school.list')
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

            Layout::modal('massImportModal',[

                Layout::rows([

                    Upload::make('school_csv')
                        ->title('File type must be in CSV format. (Ex. schools.cvs)')  
                        ->acceptedFiles('.csv'),
                ])
                
            ])
            ->title('Mass Import Schools')
            ->applyButton('Import')
            ->withoutCloseButton(),

            Layout::rows([

                Input::make('school_name')
                    ->title('School Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Colonel By Secondary School'),

                Input::make('country')
                    ->title('Country')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Canada'),

                Input::make('state_province')
                    ->title('State/Province')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Ontario'),

                Input::make('school_board')
                    ->title('School Board')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. OCDSB'),

                Input::make('address')
                    ->title('Address')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. 2381 Ogilvie Rd'),

                Input::make('zip_postal')
                    ->title('Zip/Postal')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. K1J 7N4'),

                Input::make('phone_number')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Input::make('fax')
                    ->title('Fax Number')
                    ->type('number')
                    ->horizontal()
                    ->placeholder('Ex. 546879123'),

                Input::make('teacher_name')
                    ->title('Teacher name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. John Doe'),

                Input::make('teacher_email')
                    ->title('Teacher Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Input::make('teacher_cell')
                    ->title('Teacher Contact Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 852-4563'),
                

            ]),
        ];
    }

    //this method will add a single school to the database
    public function createSchool(Request $request){

        //TODO CHECK FOR DUPLICATE SCHOOLS BEFORE ENTERING

        try{
            $formFields = $request->all();
            
            School::create($formFields);

            Toast::success('School Added Succesfully');
            
            return redirect()->route('platform.school.list');

        }catch(Exception $e){

            Alert::error('There was an error creating this school. Error Code: ' . $e);
        }
    }

    //this method will mass import schools from a csv file
    public function massImport(Request $request){


        return redirect()->route('platform.school.list');
    }

    private function csvToArray($test){


    }
}
