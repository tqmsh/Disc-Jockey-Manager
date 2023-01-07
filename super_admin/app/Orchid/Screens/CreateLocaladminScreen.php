<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
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
use Orchid\Support\Facades\Dashboard;
use Orchid\Screen\Actions\ModalToggle;

class CreateLocaladminScreen extends Screen
{
    public $requiredFields = ['firstname', 'lastname', 'email', 'county', 'password', 'phonenumber', 'school', 'state_province', 'country'];
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
        return 'Add a new Local Admin';
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
                ->method('createLocaladmin'),
                
            ModalToggle::make('Mass Import Local Admins')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.localadmin.list')
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

                    Input::make('localadmin_csv')
                        ->type('file')
                        ->title('File must be in csv format. Ex. localadmins.csv')
                        ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the local admins: <br>
                            • firstname <br>
                            • lastname <br>
                            • phonenumber <br>
                            • email <br>
                            • password <br>
                            • school <br>
                            • state_province <br>
                            • country <br>
                            • county <br>')
                ]),
            ])
            ->title('Mass Import Local Admins')
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
                    ->empty('No Selection')
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->empty('No Selection')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('No Selection')
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Select::make('county')
                    ->title('County')
                    ->empty('No Selection')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county'),
            ]),
        ];
    }

    public function createLocaladmin(Request $request){

        try{

            $localAdminTableFields = $this->getLocalAdminFields($request);

            $userTableFields = $this->getUserFields($request);


            //check for duplicate email
            if($this->validEmail($request->input('email'))){
                
                //no duplicates found
                $user = User::create($userTableFields);

                $localAdminTableFields['user_id'] = $user->id;

                Localadmin::create($localAdminTableFields);
                
                Toast::success('Local Admin Added Succesfully');

                return redirect()->route('platform.localadmin.list');
            }else{
                //duplicate email found
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){
            
            Alert::error('There was an error creating this local admin Error Code: ' . $e->getMessage());
        }
    }

    //this method will mass import schools from a csv file
    public function massImport(Request $request){

        try{

            $path = $this->validFile($request);

            if($path){

                $localadmins = $this->csvToArray($path);

                $keys = array_keys($localadmins[0]);

                //check if the user has the required values in the csv file
                foreach($this->requiredFields as $field){

                    if(!in_array($field, $keys)){
                        Toast::error('"' . $field . '"' . 'is missing in your csv file.'); return;
                    }
                }

                //loop through the array of schools and re-write the keys to insert in db
                for ($i = 0; $i < count($localadmins); $i ++){

                    if($this->validEmail($localadmins[$i]['email'])){
                        
                        $localadmins[$i]['school_id'] = $this->getSchoolID($localadmins[$i]['country'], $localadmins[$i]['school'], $localadmins[$i]['county'], $localadmins[$i]['state_province']);

                        
                        $user = User::create([
                           'firstname' => $localadmins[$i]['firstname'],
                           'lastname' => $localadmins[$i]['lastname'],
                           'phonenumber' => $localadmins[$i]['phonenumber'],
                           'email' => $localadmins[$i]['email'],
                           'password' => bcrypt($localadmins[$i]['password']),
                           'country' => $localadmins[$i]['country'],
                           'role' => 2,
                           'name' => $localadmins[$i]['firstname'],
                           'account_status' => 1,
                        ]);

                        $localadmins[$i]['user_id'] = $user->id;

                        Localadmin::create([
                           'firstname' => $localadmins[$i]['firstname'],
                           'lastname' => $localadmins[$i]['lastname'],
                           'phonenumber' => $localadmins[$i]['phonenumber'],
                           'email' => $localadmins[$i]['email'],
                           'school_id' => $localadmins[$i]['school_id'],
                           'user_id' => $localadmins[$i]['user_id'],
                           'account_status' => 1,
                           'school' => $localadmins[$i]['school']
                        ]);

                    }else{
                        array_push($this->dupes, $localadmins[$i]['email']);                    
                    }
                }

                if(!empty($this->dupes)){
                    $message = 'Accounts with these emails have not been added as they already have an account in our system: ';

                    foreach($this->dupes as $email){

                        $message .="| " . $email . " | ";
                    }

                    Alert::error($message);
                }else{

                    Toast::success('Local Admins imported successfully!');
                }


                return redirect()->route('platform.localadmin.list');
            }
        }catch(Exception $e){
            
            Alert::error('There was an error mass importing the local admins. Error Code: ' . $e->getMessage());
        }
    }

    private function validFile(Request $request){

        $path = '';

        if(!is_null($request->file('localadmin_csv'))){

            $path = $request->file('localadmin_csv')->getRealPath();

            if(!is_null($path)){

                $extension = $request->file('localadmin_csv')->extension();

                if($extension != 'csv'){

                    Toast::error('Incorrect file type.'); return false;
                }else{

                    return $path;
                }

            } else{
                
                Toast::error('An error has occured.'); return;
            }

        } else{

            Toast::error('Upload a csv file to import local admins.'); return false;
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
    private function getLocalAdminFields($request){

        try{
            $school_id = $this->getSchoolIDByReq($request);

            $localadminTableFields = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phonenumber' => $request->input('phonenumber'),
                'school' => $request->input('school'),
                'account_status' => 1,
                'user_id' => null,
                'school_id' => $school_id
            ];
            
            return $localadminTableFields;

        }catch(Exception $e){
            Alert::error('There was an error creating this local admin Error Code: ' . $e->getMessage());
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
            'role' =>2,
        ];
        
        return $userTableFields;
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
}
