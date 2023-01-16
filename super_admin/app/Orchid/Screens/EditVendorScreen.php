<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditVendorScreen extends Screen
{

    public $vendor;
    public $user;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Vendors $vendor): iterable
    {
        return [
            'vendor' => $vendor,
            'user' => User::find($vendor->user_id)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Vendor: ' . $this->vendor->company_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Vendor')
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete this vendor?')),

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
                    ->placeholder('Ex. John')
                    ->value($this->user->firstname),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Doe')
                    ->value($this->user->lastname),

                Input::make('company_name')
                    ->title('Company Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Disco Rockerz')
                    ->value($this->vendor->company_name),
                
                Input::make('website')
                    ->title('Company Website')
                    ->type('url')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. www.disco.com')
                    ->value($this->vendor->website),
                
                Select::make('category_id')
                    ->title('Category')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromQuery(Categories::query(), 'name')
                    ->value($this->vendor->category_id),
                    
                Input::make('email')
                    ->title('Company Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com')
                    ->value($this->vendor->email),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863')
                    ->value($this->vendor->phonenumber),

                Input::make('address')
                    ->title('Address')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. 1234 Main St.')
                    ->value($this->vendor->address),

                Select::make('country')
                    ->title('Country')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->vendor->country),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->vendor->state_province),

                Input::make('zip_postal')
                    ->title('Zip/Postal Code')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. K1A 0B1')
                    ->value($this->vendor->zip_postal),

                Input::make('city')
                    ->title('City')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Ottawa')
                    ->value($this->vendor->city),
            ]),
        ];
    }
    
    public function update(Vendors $vendor, Request $request)
    {
        try{

            //check for duplicate email
            if($this->validEmail($request, $vendor)){

                //email not changed
                $vendor->update($request->except(['firstname', 'lastname', 'name', '_token']));
                
                User::where('id', $vendor->user_id)->update($request->except(['website', '_token', 'city', 'state_province', 'zip_postal', 'address', 'category_id', 'company_name']));
                
                Toast::success('You have successfully updated ' . $request->input('company_name'));

                return redirect()->route('platform.vendor.list');
            
            }else{

                //duplicate email found
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){

            Alert::error('There was an error editing this vendor. Error Code: ' . $e->getMessage());
        }
    } 

    public function delete(Vendors $vendor)
    {
        try{

            $vendor->delete();
            User::where('id', $vendor->user_id)->delete();


            Toast::info('You have successfully deleted the vendor.');

            return redirect()->route('platform.vendor.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this vendor. Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($request, $vendor){
        return count(User::whereNot('id', $vendor->user_id)->where('email', $request->input('email'))->get()) == 0;
    }
}
