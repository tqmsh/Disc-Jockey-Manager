<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class SuggestVendorScreen extends Screen
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
        return 'Suggest a Vendor';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Suggest')
                ->icon('plus')
                ->method('createVendor'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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

                Input::make('company_name')
                    ->title('Company Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Disco Rockerz'),

                Select::make('category_id')
                    ->title('Category')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromQuery(Categories::query()->where('status', 1), 'name'),
                    
                Input::make('firstname')
                    ->title('First Name')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. John'),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. Doe'),

                Input::make('website')
                    ->title('Company Website')
                    ->type('url')
                    ->horizontal()
                    ->placeholder('Ex. www.disco.com'),
                    
                Input::make('email')
                    ->title('Company Email')
                    ->type('email')
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Input::make('address')
                    ->title('Address')
                    ->horizontal()
                    ->placeholder('Ex. 1234 Main St.'),

                Select::make('country')
                    ->title('Country')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Input::make('zip_postal')
                    ->title('Zip/Postal Code')
                    ->horizontal()
                    ->placeholder('Ex. K1A 0B1'),

                Input::make('city')
                    ->title('City')
                    ->horizontal()
                    ->placeholder('Ex. Ottawa'),

            ])->title('Suggest a Vendor'),
        ];
    }

    public function createVendor(Request $request){

        try{
            
            //get vendor fields

            $userTableFields = $request->except(['website', '_token', 'city', 'state_province', 'zip_postal', 'address', 'category_id', 'company_name']);

            $vendorTableFields = $request->except(['firstname', 'lastname', 'name', '_token']);

            //check for duplicate email
            if($this->validEmail($request->input('email'))){

                $user['role'] = 4;
                
                //create user
                $user = User::create($userTableFields);

                //get user id to be used as a foreign key for the vendor table
                $vendorTableFields['user_id'] = $user->id;

                //create vendor
                Vendors::create($vendorTableFields);
                
                //toast success message
                Toast::success('Vendor Suggested Succesfully! Wait until an admin approves it.');

                //redirect to vendor list
                return redirect()->route('platform.event.list');

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

}
