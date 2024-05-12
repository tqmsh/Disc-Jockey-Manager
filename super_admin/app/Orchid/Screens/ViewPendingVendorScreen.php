<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Vendors;
use App\Models\RoleUsers;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewPendingVendorLayout;

class ViewPendingVendorScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pending_vendors' => Vendors::latest('vendors.created_at')->where('vendors.account_status', 0)->filter(request(['country', 'category_id', 'state_province', 'search_input_by', 'name_filter']))->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Pending Vendors';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Accept Selected Vendors')
                ->icon('check')
                ->method('acceptVendors')
                ->confirm(__('Are you sure you want to accept the selected vendors?')),

            Button::make('Reject Selected Vendors')
                ->icon('trash')
                ->method('deleteVendors')
                ->confirm(__('Are you sure you want to delete the selected vendors?')),

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

                Group::make([

                    Select::make('country')
                        ->title('Country')
                        ->help('Type in boxes to search')
                        ->empty('No Selection')
                        ->fromModel(Vendors::class, 'country', 'country'),

                    Select::make('category_id')
                        ->title('Category')
                        ->empty('No Selection')
                        ->fromQuery(Categories::query(), 'name'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No Selection')
                        ->fromModel(Vendors::class, 'state_province', 'state_province'),

                    Select::make('search_input_by')
                        ->title('Search By:')
                        ->options([
                            'company_name'   => 'Company Name',
                            'email' => 'Email',

                        ]),


                    Input::make('name_filter')
                        ->title('Enter:')
                        ->placeholder('No input')

            ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewPendingVendorLayout::class
        ];
    }

    public function filter(){
        return redirect()->route('platform.pendingvendor.list', request(['country', 'category_id', 'state_province', 'search_input_by', 'name_filter']));

    }

    public function acceptVendors(Request $request){

        //get all vendors from post request
        $vendors = $request->get('vendors');

        try{
            //if the array is not empty
            if(!empty($vendors)){

                //loop through the vendors set account status to 1 and give them permissions to access dashboard
                foreach($vendors as $vendor_id){

                    User::where('id', $vendor_id)->update(['account_status' => 1]);

                    Vendors::where('user_id', $vendor_id)->update(['account_status' => 1]);

                    RoleUsers::create([
                        'user_id' => $vendor_id,
                        'role_id' => 4,
                    ]);
                }

                Toast::success('Selected vendors accepted succesfully');

            }else{
                Toast::warning('Please select vendors in order to accept them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to accept the selected vendors. Error Message: ' . $e->getMessage());
        }
    }

    public function deleteVendors(Request $request){

        //get all vendors from post request
        $vendor_ids = $request->get('vendors');

        try{

            //if the array is not empty
            if(!empty($vendor_ids)){

                User::whereIn('id', $vendor_ids)->delete();

                Toast::success('Selected vendors deleted succesfully');

            }else{
                Toast::warning('Please select vendors in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected vendors. Error Message: ' . $e->getMessage());
        }
    }

    public function deleteVendor($vendor_id){
        // delete vendor from the vendors table
        Vendors::where('user_id', $vendor_id)->delete();

        // delete vendor from the users table
        User::where('id', $vendor_id)->delete();
    }
}
