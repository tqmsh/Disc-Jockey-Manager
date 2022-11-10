<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
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
            
            Button::make('Add')
                ->icon('plus')
                ->method('createSchool'),

            ModalToggle::make('Mass Import Schools')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),


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
                        ->title('File type must be in CSV format. (Ex. schools.csv)')  
                        ->acceptedFiles('.csv'),
                ])
                
            ])
            ->title('Mass Import Schools')
            ->applyButton('Import')
            ->withoutCloseButton(),

            Layout::rows([

                Input::make('nces_id')
                    ->title('NCES ID')
                    ->type('number')
                    ->horizontal()
                    ->required()
                    ->placeholder('Ex. 546879123'),

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

                Input::make('metropolitan_region')
                    ->title('Metropolitan Region')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. Greater Ottawa Metropolitan Area'),

                Input::make('city_municipality')
                    ->title('City/Municipality')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. Ottawa'),

                Input::make('county')
                    ->title('County')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. Suffolk County'),

                Input::make('website')
                    ->title('Website')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. www.colonelby.com'),

                Input::make('school_data')
                    ->title('School Data')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. Ex. www.colonelby.com?Search=1&InstName=newton&State=25&SchoolType=1'),

                Input::make('firstname')
                    ->title('Teacher First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. John '),

                Input::make('lastname')
                    ->title('Teacher Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Doe'),


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

                Input::make('total_students')
                    ->title('Total Students')
                    ->type('number')
                    ->horizontal()
                    ->placeholder('Ex. 1024'),
            ]),
        ];
    }

    //this method will add a single school to the database
    public function createSchool(Request $request){

        try{

            //check for duplicate schools
            if($this->validSchool($request) && $this->validEmail($request)){

                //collect the school table values and user table values
                $userTableFields = $this->getUserFields($request);
                $schoolTableFields = $this->getSchoolFields($request);

                //make the user first, then get the id and insert that along with other fields in the school table
                User::create($userTableFields);

                $schoolTableFields['teacher_id'] = User::where('email', $request->input('teacher_email'))->get('id')->value('id');
                
                School::create($schoolTableFields);

                Toast::success('You have successfully created ' . $request->input('school_name') . '.');

                return redirect()->route('platform.school.list');
            
            }else{

                //duplicate school found
                Toast::error('School already exists.');
            }

        }catch(Exception $e){

            Alert::error('There was an error creating this school. Error Code: ' . $e);
        }
    }

    //this method will mass import schools from a csv file
    public function massImport(Request $request){

        try{

            //convert csv file to array then insert in db


            return redirect()->route('platform.school.list');

        }catch(Exception $e){
            
            Alert::error('There was an error mass importing the schools. Error Code: ' . $e);
        }

    }

    private function csvToArray(){


    }

    //this functions returns the values that need to be inserted in the school table in the db
    private function getSchoolFields($request){
        $schoolTableFields = [
            'nces_id' => $request->input('nces_id'),
            'school_name' => $request->input('school_name'),
            'country' => $request->input('country'),
            'state_province' => $request->input('state_province'),
            'address' => $request->input('address'),
            'zip_postal' => $request->input('zip_postal'),
            'fax' => $request->input('fax'),
            'metropolitan_region' => $request->input('metropolitan_region'),
            'county' => $request->input('county'),
            'website' => $request->input('website'),
            'school_data' => $request->input('school_data'),
            'total_students' => $request->input('total_students'),
            'city_municipality' => $request->input('city_municipality'),
            'school_board' => $request->input('school_board'),
            'phone_number' => $request->input('phone_number'),
            'teacher_id' => null,
        ];
        
        return $schoolTableFields;
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){
        $userTableFields = [
            'name' => $request->input('firstname') . $request->input('lastname'),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'phonenumber' => $request->input('teacher_cell'),
            'email' => $request->input('teacher_email'),
            'role' => 'teacher',
            'country' => $request->input('country'),
        ];
        
        return $userTableFields;
    }

    //check for duplicate emails
    private function validEmail($request){
        return count(User::where('email', $request->input('email'))->get()) == 0;
    }

    //this method checks for duplicate schools
    private function validSchool($request){

        return count(School::where('school_name', $request->input('school_name'))->where('school_board', $request->input('school_board'))->where('state_province', $request->input('state_province'))->get()) == 0;
    }
}
