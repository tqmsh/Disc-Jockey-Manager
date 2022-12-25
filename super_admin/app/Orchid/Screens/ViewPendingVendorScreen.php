<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Vendors;
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
            'pending_vendors' => Vendors::latest('vendors.created_at')->where('vendors.account_status', 0)->filter(request(['country', 'category_id', 'state_province']))->paginate(10),
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
                        ->empty('No selection')
                        ->fromModel(Vendors::class, 'country', 'country'),

                    Select::make('category_id')
                        ->title('Category')
                        ->empty('No selection')
                        ->fromQuery(Categories::query(), 'name'),
                    
                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->fromModel(Vendors::class, 'state_province', 'state_province'),

                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewPendingVendorLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/pendingvendors?' 
                    .'&country=' . $request->get('country')
                    .'&category_id=' . $request->get('category_id')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function acceptVendors(Request $request){

        //get all vendors from post request
        $vendors = $request->get('vendors');
        
        try{
            //if the array is not empty
            if(!empty($vendors)){

                //loop through the vendors set account status to 1 and give them permissions to access dashboard
                foreach($vendors as $vendor_id){

                    $userTableFields = [
                        'account_status' => 1,
                        'permissions' => '{"platform.index":true}'
                    ];

                    $vendorFields = [
                        'account_status' => 1,
                    ];

                    User::where('id', $vendor_id)->update($userTableFields);

                    Vendors::where('user_id', $vendor_id)->update($vendorFields);
                }

                Toast::success('Selected vendors accepted succesfully');

            }else{
                Toast::warning('Please select vendors in order to accept them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to accept the selected vendors. Error Message: ' . $e);
        }
    }

    public function deleteVendors(Request $request){  

        //get all vendors from post request
        $vendor_ids = $request->get('vendors');
        
        try{

            //if the array is not empty
            if(!empty($vendor_ids)){

                //loop through the vendors and delete them from db
                foreach($vendor_ids as $vendor_id){
                    $this->deleteVendor($vendor_id);
                }

                Toast::success('Selected vendors deleted succesfully');

            }else{
                Toast::warning('Please select vendors in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected vendors. Error Message: ' . $e);
        }
    }

    public function deleteVendor($vendor_id){
        // delete vendor from the vendors table
        Vendors::where('user_id', $vendor_id)->delete();
        
        // delete vendor from the users table
        User::where('id', $vendor_id)->delete();
    }
}
