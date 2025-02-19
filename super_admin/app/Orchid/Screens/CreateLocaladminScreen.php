<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use App\Models\RoleUsers;
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

class CreateLocaladminScreen extends Screen
{
    public $requiredFields = ['firstname', 'lastname', 'email', 'county', 'password', 'phonenumber', 'school', 'state_province', 'country'];
    public $dupes =[];
    public $localadmin;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Localadmin $localadmin, Request $request): iterable
    {
        $localadmin->firstname = $request->input('firstname') ?? "";
        $localadmin->lastname = $request->input('lastname') ?? "";
        $localadmin->name = $request->input('name') ?? "";
        $localadmin->phonenumber = $request->input('phonenumber') ?? "";
        $localadmin->email = $request->input('email') ?? "";
        $localadmin->password = $request->input('password') ?? "";
        $localadmin->school = $request->input('school') ?? "";
        $localadmin->country = $request->input('country') ?? "";
        $localadmin->state_province = $request->input('state_province') ?? "";
        $localadmin->county = $request->input('county') ?? "";
        $localadmin->city_municipality = $request->input('city_municipality') ?? "";

        return [
            'localadmin'=>$localadmin
        ];
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
                            • county (include column but leave blank for Canadian schools) <br>
                            • city_municipality (include column but leave blank for American schools) <br>'),
                    Link::make('Download Sample CSV')
                        ->icon('download')
                        ->href('/sample_local_admins_upload.csv')
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
                    ->placeholder('Ex. John')
                    ->value($this->localadmin->firstname),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Doe')
                    ->value($this->localadmin->lastname),

                Input::make('name')
                    ->title('Username')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. KingKhan435')
                    ->value($this->localadmin->name),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863')
                    ->value($this->localadmin->phonenumber),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com')
                    ->value($this->localadmin->email),

                Password::make('password')
                    ->title('Password')
                    ->type('password')
                    ->required()
                    ->horizontal()
                    ->value($this->localadmin->password),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->localadmin->school),

                Select::make('country')
                    ->title('Country')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->localadmin->country),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->required()
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->localadmin->state_province),

                Select::make('county')
                    ->title('County')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county')
                    ->value($this->localadmin->county),

                Select::make('city_municipality')
                    ->title('City/Municipality')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'city_municipality', 'city_municipality')
                    ->value($this->localadmin->city_municipality),
            ]),
        ];
    }

    public function createLocaladmin(Request $request){

        try{

            $localAdminTableFields = $this->getLocalAdminFields($request);

            $userTableFields = $this->getUserFields($request);


            //check for duplicate email
            if($this->validEmail($request->input('email')) && $this->validUserName($request->input('name'))){

                //no duplicates found
                $user = User::create($userTableFields);

                $localAdminTableFields['user_id'] = $user->id;

                Localadmin::create($localAdminTableFields);

                RoleUsers::create([
                    'user_id' => $user->id,
                    'role_id' => 2
                ]);

                Toast::success('Local Admin Added Succesfully');

                return redirect()->route('platform.localadmin.list');
            }else{
                //duplicate email found
                if(!$this->validEmail($request->input('email'))){
                    Toast::error('Email already exists.');

                }else{
                    Toast::error('Username already exists.');

                }
                return redirect()->route('platform.localadmin.create', request(['firstname', 'lastname', 'name', 'phonenumber', 'email', 'password', 'school', 'country', 'state_province', 'county', 'city_municipality']));

            }

        }catch(Exception $e){

            Alert::error('There was an error creating this local admin Error Code: ' . $e->getMessage());
            return redirect()->route('platform.localadmin.create', request(['firstname', 'lastname', 'name', 'phonenumber', 'email', 'password', 'school', 'country', 'state_province', 'county', 'city_municipality']));
        }
    }

    //this method will mass import localadmins from a csv file
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

                //loop through the array of local admins and re-write the keys to insert in db
                for ($i = 0; $i < count($localadmins); $i ++){

                    if($this->validEmail($localadmins[$i]['email'])) {

                        $localadmins[$i]['school_id'] = $this->getSchoolID($localadmins[$i]['country'], $localadmins[$i]['school'], $localadmins[$i]['county'], $localadmins[$i]['city_municipality'], $localadmins[$i]['state_province']);

                        $user = User::create([
                           'name' => $localadmins[$i]['firstname'],
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

                        RoleUsers::create([
                            'user_id' => $user->id,
                            'role_id' => 2,
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

                if($extension != 'csv' && $extension != 'txt'){

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

    private function validUserName($username){
        return count(User::where('name', $username)->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getLocalAdminFields($request){
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
        $school_query = School::where('school_name', $request->input('school'))
            ->where('state_province', $request->input('state_province'))
            ->where('country', $request->input('country'));

        if ($request->input('country') == 'USA') {
            $school_query = $school_query->where('county', $request->input('county'));
        } else {
            $school_query = $school_query->where('city_municipality', $request->input('city_municipality'));
        }
        $school = $school_query->get();

        if(is_null($school)){

            throw New Exception('You are trying to enter a invalid school');

        } else{

            return $school->value('id');
        }
    }

    private function getSchoolID($country, $school, $county, $city_municipality, $state_province){
        $school_query = School::where('school_name', $school)
            ->where('state_province', $state_province)
            ->where('country', $country);

        if ($country == 'USA') {
            $school_query = $school_query->where('county', $county);
        } else {
            $school_query = $school_query->where('city_municipality', $city_municipality);
        }
        $school = $school_query->get();

        if(is_null($school)){

            throw New Exception('You are trying to enter a invalid school');

        } else{

            return $school->value('id');
        }
    }
}
