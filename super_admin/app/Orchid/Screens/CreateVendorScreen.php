<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use App\Models\Categories;
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

class CreateVendorScreen extends Screen
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
                
                Input::make('company_website')
                    ->title('Company Website')
                    ->type('url')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. www.disco.com'),
                
                Select::make('category_id')
                    ->title('Category')
                    ->empty('No Selection')
                    ->required()
                    ->horizontal()
                    ->fromQuery(Categories::query(), 'name'),
                    
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

                Input::make('address')
                    ->title('Address')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. 1234 Main St.'),

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


            //check for duplicate email
            if($this->validEmail($request->input('email'))){
                
                //no duplicates found
                //create user
                User::create($userTableFields);

                //get user id to be used as a foreign key for the vendor table
                $vendorTableFields['user_id'] = User::where('email', $request->input('email'))->get('id')->value('id');

                //create vendor
                Vendors::create($vendorTableFields);
                
                //toast success message
                Toast::success('Vendor Added Succesfully');

                //redirect to vendor list
                return redirect()->route('platform.vendor.list');

            }else{

                //duplicate email found
                //toast error message
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){
            
            //toast error message
            Alert::error('There was an error creating this vendor Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($email){
        return count(User::where('email', $email)->get()) == 0;
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
                'website' => $request->input('company_website'),
                'phonenumber' => $request->input('phonenumber'),
                'account_status' => 1,
            ];
            
            return $vendorTableFields;

        }catch(Exception $e){
            Alert::error('There was an error creating this vendor. Error Code: ' . $e->getMessage);
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
            'permissions' => Dashboard::getAllowAllPermission(),
            'phonenumber' => $request->input('phonenumber'),
            'role' =>'vendor',
        ];
        
        return $userTableFields;
    }
}
