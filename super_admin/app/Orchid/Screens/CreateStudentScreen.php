<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\RoleUsers;
use Orchid\Screen\Screen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Password;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;

class CreateStudentScreen extends Screen
{
    public $requiredFields = ['firstname', 'lastname', 'email', 'county', 'password', 'phonenumber', 'allergies', 'grade', 'school', 'state_province', 'country'];
    public $dupes =[];

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
        return 'Add a New Student';
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
                ->method('createStudent'),
                
            ModalToggle::make('Mass Import Students')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list')
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

                    Input::make('student_csv')
                        ->type('file')
                        ->title('File must be in csv format. Ex. students.csv')
                        ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the students: <br>
                            • firstname <br>
                            • lastname <br>
                            • grade <br>
                            • phonenumber <br>
                            • email <br>
                            • password <br>
                            • allergies <br>
                            • school <br>
                            • state_province <br>
                            • country <br>
                            • county <br>')
                ]),
            ])
            ->title('Mass Import Students')
            ->applyButton('Import')
            ->withoutCloseButton(),

            Layout::rows([

                Input::make('firstname')
                    ->title('First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. John'),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Doe'),

                Input::make('name')
                    ->title('Username')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. KingKhan435'),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Password::make('password')
                    ->title('Password')
                    ->type('password')
                    ->required()
                    ->horizontal(),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->empty('Start typing to search...')
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to search...')
                    ->required()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to search...')
                    ->required()
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Select::make('county')
                    ->title('County')
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->fromModel(School::class, 'county', 'county'),

                Select::make('grade')
                    ->title('Grade')
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->options([
                        '9' => 9,
                        '10' => 10,
                        '11' => 11,
                        '12' => 12,
                    ]),

                Select::make('allergies')
                    ->title('Allergies')
                    ->horizontal()
                    ->empty('Start typing to search...')
                    ->options([
                        'Peanuts' => 'Peanuts',
                        'Tree Nuts' => 'Tree Nuts',
                        'Shellfish' => 'Shellfish',
                        'Milk' => 'Milk',
                        'Eggs' => 'Eggs',
                        'Wheat' => 'Wheat',
                        'Soy' => 'Soy',
                        'Fish' => 'Fish',
                    ]),
            ]),
        ];
    }

    public function createStudent(Request $request){

        try{

            //get the student table fields
            $studentTableFields = $this->getStudentFields($request);

            //get the user table fields
            $userTableFields = $this->getUserFields($request);

            //check for duplicate email
            if($this->validEmail($request->input('email'))){
                
                //no duplicates found
                //create the user
                $user = User::create($userTableFields);

                //add the user id to the student table fields
                $studentTableFields['user_id'] = $user->id;
                
                //create the student
                Student::create($studentTableFields);

                //create a role user entry for the student
                RoleUsers::create([
                    'user_id' => $user->id,
                    'role_id' => 3,
                ]);

                //show a success toast
                Toast::success('Student Added Succesfully');

                //redirect to the student index
                return redirect()->route('platform.student.list');
            
            }else{
                //duplicate email found
                //show an error toast
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){

            //show an error toast
            Alert::error('There was an error creating this student. Error Code: ' . $e->getMessage());
        }
}
    
    //this method will mass import schools from a csv file
    public function massImport(Request $request){

        try{

            $path = $this->validFile($request);

            if($path){

                $students = $this->csvToArray($path);

                $keys = array_keys($students[0]);

                //check if the user has the required values in the csv file
                foreach($this->requiredFields as $field){

                    if(!in_array($field, $keys)){
                        Toast::error('"' . $field . '"' . 'is missing in your csv file.'); return;
                    }
                }

                //loop through the array of schools and re-write the keys to insert in db
                for ($i = 0; $i < count($students); $i ++){

                    if($this->validEmail($students[$i]['email'])){
                        
                        $students[$i]['school_id'] = $this->getSchoolID($students[$i]['country'], $students[$i]['school'], $students[$i]['county'], $students[$i]['state_province']);
                        
                        $student = [
                            'firstname' => $students[$i]['firstname'],
                            'lastname' => $students[$i]['lastname'],
                            'phonenumber' => $students[$i]['phonenumber'],
                            'email' => $students[$i]['email'],
                            'password' => bcrypt($students[$i]['password']),
                            'country' => $students[$i]['country'],
                            'role' => 3,
                            'name' => $students[$i]['firstname'],
                            'account_status' => 1,
                        ];

                        $user = User::create($student);
                        
                        $student['user_id'] = $user->id;

                        $student = [
                            'firstname' => $students[$i]['firstname'],
                            'lastname' => $students[$i]['lastname'],
                            'phonenumber' => $students[$i]['phonenumber'],
                            'email' => $students[$i]['email'],
                            'grade' => $students[$i]['grade'],
                            'school_id' => $students[$i]['school_id'],
                            'allergies' => $students[$i]['allergies'],
                            'user_id' => $student['user_id'],
                            'account_status' => 1,
                            'school' => $students[$i]['school'],
                        ];

                        Student::create($student);

                        RoleUsers::create([
                            'user_id' => $user->id,
                            'role_id' => 3,
                        ]);

                    }else{
                        array_push($this->dupes, $students[$i]['email']);                    
                    }
                }

                if(!empty($this->dupes)){
                    $message = 'Accounts with these emails have not been added as they already have an account in our system: ';

                    foreach($this->dupes as $email){

                        $message .="| " . $email . " | ";
                    }

                    Alert::error($message);
                }else{

                    Toast::success('Students imported successfully!');
                }


                return redirect()->route('platform.student.list');
            }
        }catch(Exception $e){
            
            Alert::error('There was an error mass importing the students. Error Code: ' . $e->getMessage());
        }
    }

    private function validFile(Request $request){

        $path = '';

        if(!is_null($request->file('student_csv'))){

            $path = $request->file('student_csv')->getRealPath();

            if(!is_null($path)){

                $extension = $request->file('student_csv')->extension();

                if($extension != 'csv' && $extension != 'txt'){

                    Toast::error('Incorrect file type.'); return false;
                }else{

                    return $path;
                }

            } else{
                
                Toast::error('An error has occured.'); return;
            }

        } else{

            Toast::error('Upload a csv file to import students.'); return false;
        }
    }
    
    //this function will convert the csv file to an array
    private function csvToArray($filename = '', $delimiter = ','){
        
        if (!file_exists($filename) || !is_readable($filename)){
            Alert::error('There has been an error finding this file.');
            return;
        }

        $header = null;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== false){

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }

            fclose($handle);
        }

        return $data;
    }

    //check for duplicate emails
    private function validEmail($email){
        return count(User::where('email', $email)->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getStudentFields($request){

        $school_id = $this->getSchoolIDByReq($request);

        $studentTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'school' => $request->input('school'),
            'school_id' => $school_id,
            'grade' => $request->input('grade'),
            'account_status' => 1,
            'event_id' => $request->input('event_id'),
            'allergies' => $request->input('allergies'),
            'ticketstatus'=> $request->input('ticketstatus'),
        ];
        
        return $studentTableFields;
    }

    private function getSchoolIDByReq($request){
        $school_id = School::where('school_name', $request->input('school'))
                            ->where('county', $request->input('county'))
                            ->where('state_province', $request->input('state_province'))
                            ->where('country', $request->input('country'))
                            ->get('id')->value('id');

        if(is_null($school_id)){

            throw New Exception('You are trying to enter a invalid school');

        } else{

            return $school_id;
        }
    }

    private function getSchoolID($country, $school, $county, $state_province){
        $school_id = School::where('school_name', $school)
                            ->where('county', $county)
                            ->where('state_province', $state_province)
                            ->where('country', $country)
                            ->get('id')->value('id');

        if(is_null($school_id)){

            throw New Exception('You are trying to enter a invalid school');

        } else{

            return $school_id;
        }
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'name' => $request->input('name'),
            'country' => $request->input('country'),
            'account_status' => 1,
            'phonenumber' => $request->input('phonenumber'),
            'remember_token' => Str::random(10),
            'role' =>3,
        ];
        
        return $userTableFields;
    }
}
