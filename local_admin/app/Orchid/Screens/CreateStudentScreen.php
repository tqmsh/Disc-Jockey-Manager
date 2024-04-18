<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\RoleUsers;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
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
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Dashboard;
use Orchid\Screen\Actions\ModalToggle;




class CreateStudentScreen extends Screen
{
    public $requiredFields = ['firstname', 'lastname', 'email', 'password', 'phonenumber', 'allergies', 'grade'];
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
                            • allergies <br>'),
                    Link::make('Download Sample CSV')
                        ->icon('download')
                        ->href('/sample_students_upload.csv')
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

                Select::make('grade')
                    ->title('Grade')
                    ->horizontal()
                    ->required()
                    ->empty('No Selection')
                    ->options([
                        '9' => 9,
                        '10' => 10,
                        '11' => 11,
                        '12' => 12,
                    ]),

                    Select::make('allergies')
                    ->title('Allergies')
                    ->horizontal()
                    ->allowAdd()
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

                Button::make('Add')
                ->icon('plus')
                ->type(Color::PRIMARY())
                ->method('createStudent'),

            ]),
        ];
    }


    public function createStudent(Request $request){
        try{
            
            $request->validate([
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'name' => 'required|max:255',
                'phonenumber' => 'required|max:20',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',
                'grade' => 'required|integer|in:9,10,11,12',
                'allergies' => 'nullable|max:255',
            ]);
            


            //get the student table fields
            $studentTableFields = $this->getStudentFields($request);

            //get the user table fields
            $userTableFields = $this->getUserFields($request);


            //no duplicates found
            //create user
            $user = User::create($userTableFields);

            


            //add the user id to the student table fields
            $studentTableFields['user_id'] = $user->id;
            
            //create student
            Student::create($studentTableFields);

            //attach the student to the role of student
            RoleUsers::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);
            
            //notify the user
            Toast::success('Student Added Succesfully');


            // SENDY API

            $your_installation_url = 'https://pod01.growthmail.net'; //Your Sendy installation (without the trailing slash)
            $list = 'cbSDy4cjl2iW4epIArqbfg'; //Can be retrieved from "View all lists" page
            $api_key = $_ENV['SENDY_API_KEY']; //Can be retrieved from your Sendy's main settings
            $success_url = 'http://google.com'; //URL user will be redirected to if successfully subscribed
            $fail_url = 'http://yahoo.com'; //URL user will be redirected to if subscribing fails

            $postdata = http_build_query(
                array(
                'name' => $request->firstname . ' ' . $request->lastname,
                'email' => $request->email,
                'list' => $list,
                'api_key' => $api_key,
                'boolean' => 'true'
                )
            );
            
            $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
            $context  = stream_context_create($opts);
            $result = file_get_contents($your_installation_url.'/subscribe', false, $context);

            //redirect to the student list
            return redirect()->route('platform.student.list');

        }catch(Exception $e){

            //notify the user
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
                        Toast::error('There are missing field(s) in your csv file.'); return;
                    }
                }

                //loop through the array of schools and re-write the keys to insert in db
                for ($i = 0; $i < count($students); $i ++){

                    if($this->validEmail($students[$i]['email'])){
                        
                        $students[$i]['school_id'] = Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id');
                        
                        $user = User::create([
                            'firstname' => $students[$i]['firstname'], 
                            'lastname' => $students[$i]['lastname'], 
                            'phonenumber' => $students[$i]['phonenumber'], 
                            'email' => $students[$i]['email'], 
                            'password' => bcrypt($students[$i]['password']), 
                            'country' => Auth::user()->country, 
                            'role' => 3, 
                            'name' => $students[$i]['firstname'], 
                            'account_status' => 1, 
                        ]);
                        
                        $students[$i]['user_id'] = $user->id;

                        Student::create([
                            'firstname' => $students[$i]['firstname'], 
                            'lastname' => $students[$i]['lastname'], 
                            'phonenumber' => $students[$i]['phonenumber'], 
                            'email' => $students[$i]['email'], 
                            'grade' => $students[$i]['grade'], 
                            'school_id' => $students[$i]['school_id'], 
                            'allergies' => $students[$i]['allergies'], 
                            'user_id' => $students[$i]['user_id'], 
                            'account_status' => 1, 
                            'school' => Localadmin::where('user_id', Auth::user()->id)->get('school')->value('school'),
                        ]);

                        RoleUsers::create([
                            'user_id' => $user->id,
                            'role_id' => 3,
                        ]);
                            
                        // SENDY API
                        $your_installation_url = 'https://pod01.growthmail.net'; //Your Sendy installation (without the trailing slash)
                        $list = 'cbSDy4cjl2iW4epIArqbfg'; //Can be retrieved from "View all lists" page
                        $api_key = $_ENV['SENDY_API_KEY']; //Can be retrieved from your Sendy's main settings
                        $success_url = 'http://google.com'; //URL user will be redirected to if successfully subscribed
                        $fail_url = 'http://yahoo.com'; //URL user will be redirected to if subscribing fails

                        $postdata = http_build_query(
                            array(
                            'name' => $students[$i]['firstname'] . ' ' . $students[$i]['lastname'],
                            'email' => $students[$i]['email'],
                            'list' => $list,
                            'api_key' => $api_key,
                            'boolean' => 'true'
                            )
                        );
                        
                        $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
                        $context  = stream_context_create($opts);
                        $result = file_get_contents($your_installation_url.'/subscribe', false, $context);

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

        // First, check if the request has the file
        if(!is_null($request->file('student_csv'))){

            // Get the path to the file
            $path = $request->file('student_csv')->getRealPath();

            // If the path to the file is not null
            if(!is_null($path)){

                // Get the extension of the file
                $extension = $request->file('student_csv')->extension();

                // Check if the extension is csv
                if($extension != 'csv' && $extension != 'txt'){

                    // If not, display an error message
                    Toast::error('Incorrect file type.'); return false;
                }else{

                    // If it is, return the path
                    return $path;
                }

            } else{
                
                // If the path is null, display an error message
                Toast::error('An error has occured.'); return;
            }

        } else{

            // If the request does not have the file, display an error message
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

        $school = School::where('id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->first();

        $studentTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'school' => $school->school_name,
            'school_id' => $school->id,
            'grade' => $request->input('grade'),
            'account_status' =>1,
            'event_id' => $request->input('event_id'),
            'allergies' => $request->input('allergies'),
            'ticketstatus'=> $request->input('ticketstatus'),
        ];
        
        return $studentTableFields;
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'name' => $request->input('name'),
            'country' => Auth::user()->country,
            'account_status' => 1,
            'phonenumber' => $request->input('phonenumber'),
            'remember_token' => Str::random(10),
            'role' =>3,
        ];
        
        return $userTableFields;
    }
}
