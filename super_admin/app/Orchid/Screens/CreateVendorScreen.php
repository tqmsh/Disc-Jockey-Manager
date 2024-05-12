<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Vendors;
use App\Models\RoleUsers;
use Orchid\Screen\Screen;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use App\Models\VendorPaidRegions;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Password;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;

class CreateVendorScreen extends Screen
{
    public $requiredFields = ['country', 'company_name', 'firstname', 'lastname', 'category_name', 'address', 'city', 'state_province', 'zip_postal', 'phonenumber', 'website', 'email', 'password'];

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
        return 'Create a Vendor';
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
                ->method('createVendor'),

            ModalToggle::make('Mass Import Vendors')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.vendor.list')
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

                    Input::make('vendor_csv')
                        ->type('file')
                        ->title('File must be in csv format. Ex. vendors.csv')
                        ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the vendors: <br>
                            • firstname <br>
                            • lastname <br>
                            • email <br>
                            • password <br>
                            • company_name <br>
                            • city <br>
                            • category_name <br>
                            • address <br>
                            • country <br>
                            • state_province <br>
                            • zip_postal <br>
                            • phonenumber <br>
                            • website <br>'),
                    Link::make('Download Sample CSV')
                        ->icon('download')
                        ->href('/sample_vendors_upload.csv')
                ]),
            ])
                ->title('Mass Import Vendors')
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

                Input::make('company_name')
                    ->title('Company Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Disco Rockerz'),

                Input::make('website')
                    ->title('Company Website')
                    ->type('url')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. https://disco.com'),

                Select::make('category_id')
                    ->title('Category')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromQuery(Categories::query()->where('status', 1), 'name'),

                Input::make('email')
                    ->title('Company Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Password::make('password')
                    ->title('Password')
                    ->type('password')
                    ->required()
                    ->horizontal(),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Select::make('region_ids')
                    ->title('Paid Regions')
                    ->empty('No Selection')
                    ->fromModel(Region::class, 'name', 'id')
                    ->horizontal()
                    ->multiple()
                    ->help('Select the paid regions you want to add to the vendors')
                    ->placeholder('Start typing to search...'),

                Input::make('address')
                    ->title('Address')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. 1234 Main St.'),

                Select::make('country')
                    ->title('Country')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Input::make('zip_postal')
                    ->title('Zip/Postal Code')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. K1A 0B1'),

                Input::make('city')
                    ->title('City')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Ottawa'),

            ]),
        ];
    }

    public function createVendor(Request $request){

        try{

            //get vendor fields
            $vendorTableFields = $this->getVendorFields($request);

            //get user fields
            $userTableFields = $this->getUserFields($request);

            $paidRegions = $request->input('region_ids');


            //check for duplicate email
            if($this->validEmail($request->input('email')) && $this->validUserName($request->input('name'))){

                //no duplicates found
                //create user
                $user = User::create($userTableFields);

                //get user id to be used as a foreign key for the vendor table
                $vendorTableFields['user_id'] = $user->id;

                //create vendor
                Vendors::create($vendorTableFields);

                //give them permissions to enter the application
                RoleUsers::create([
                    'user_id' => $user->id,
                    'role_id' => 4
                ]);

                if($paidRegions != null){

                    //create the vendor paid regions
                    foreach($paidRegions as $region){
                        VendorPaidRegions::firstOrCreate([
                            'user_id' => $user->id,
                            'region_id' => $region
                        ]);
                    }
                }

                //toast success message
                Toast::success('Vendor Added Succesfully');

                //redirect to vendor list
                return redirect()->route('platform.vendor.list');

            }else{

                //duplicate email found
                //toast error message
                Toast::error('Email or Username already exists.');
            }

        }catch(Exception $e){

            //toast error message
            Alert::error('There was an error creating this vendor Error Code: ' . $e->getMessage());
        }
    }

    //this method will mass import vendors from a csv file
    public function massImport(Request $request){
        try{

            $path = $this->validFile($request);

            if($path){

                $vendors = $this->csvToArray($path);

                $keys = array_keys($vendors[0]);

                //check if the user has the required values in the csv file
                foreach($this->requiredFields as $field){

                    if(!in_array($field, $keys)){
                        Toast::error('"' . $field . '"' . 'is missing in your csv file.'); return;
                    }
                }

                //loop through the array of schools and re-write the keys to insert in db
                for ($i = 0; $i < count($vendors); $i ++){

                    if($this->validEmail($vendors[$i]['email'])){

                        $vendor = [
                            'firstname' => $vendors[$i]['firstname'],
                            'lastname' => $vendors[$i]['lastname'],
                            'phonenumber' => $vendors[$i]['phonenumber'],
                            'email' => $vendors[$i]['email'],
                            'password' => bcrypt($vendors[$i]['password']),
                            'country' => $vendors[$i]['country'],
                            'role' => 4,
                            'name' => $vendors[$i]['firstname'],
                            'account_status' => 1,
                        ];

                        $user = User::create($vendor);

                        $vendor['user_id'] = $user->id;

                        $vendor = [
                            'phonenumber' => $vendors[$i]['phonenumber'],
                            'email' => $vendors[$i]['email'],
                            'company_name' => $vendors[$i]['company_name'],
                            'category_id' => $this->getCategoryId($vendors[$i]['category_name']),
                            'country' => $vendors[$i]['country'],
                            'user_id' => $vendor['user_id'],
                            'account_status' => 1,
                            'address' => $vendors[$i]['address'],
                            'website' => $vendors[$i]['website'],
                            'city' => $vendors[$i]['city'],
                            'state_province' => $vendors[$i]['state_province'],
                            'zip_postal' => $vendors[$i]['zip_postal'],
                        ];

                        Vendors::create($vendor);

                        RoleUsers::create([
                            'user_id' => $user->id,
                            'role_id' => 4
                        ]);

                    }else{
                        array_push($this->dupes, $vendors[$i]['email']);
                    }
                }

                if(!empty($this->dupes)){

                    $message = 'Accounts with these emails have not been added as they already have an account in our system: ';

                    foreach($this->dupes as $email){

                        $message .="| " . $email . " | ";
                    }

                    Alert::error($message);
                }else{

                    Toast::success('Vendors imported successfully!');
                }

                return redirect()->route('platform.vendor.list');
            }
        }catch(Exception $e){

            Alert::error('There was an error mass importing the Vendors. Error Code: ' . $e->getMessage());
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

    private function validFile(Request $request){

        $path = '';

        if(!is_null($request->file('vendor_csv'))){

            $path = $request->file('vendor_csv')->getRealPath();

            if(!is_null($path)){

                $extension = $request->file('vendor_csv')->extension();

                if($extension != 'csv' && $extension != 'txt'){

                    Toast::error('Incorrect file type.'); return false;
                }else{

                    return $path;
                }

            } else{

                Toast::error('An error has occured.'); return;
            }

        } else{

            Toast::error('Upload a csv file to import vendors.'); return false;
        }
    }

    private function getCategoryId($category_name){

        $category = Categories::where('name', 'LIKE', '%'.$category_name.'%')->get();

        if(count($category) == 0){
            return null;
        }else{
            return $category->value('id');
        }
    }

    //check for duplicate emails
    private function validEmail($email){
        return count(User::where('email', $email)->get()) == 0;
    }

    private function validUserName($username){
        return count(User::where('name', $username)->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getVendorFields($request){

        try{

            $vendorTableFields = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'company_name' => $request->input('company_name'),
                'category_id' => $request->input('category_id'),
                'address' => $request->input('address'),
                'country' => $request->input('country'),
                'state_province' => $request->input('state_province'),
                'zip_postal' => $request->input('zip_postal'),
                'city' => $request->input('city'),
                'website' => $request->input('website'),
                'phonenumber' => $request->input('phonenumber'),
                'account_status' => 1,
            ];

            return $vendorTableFields;

        }catch(Exception $e){
            Alert::error('There was an error creating this vendor. Error Code: ' . $e->getMessage());
        }
    }

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
            'role' =>4,
        ];

        return $userTableFields;
    }
}
