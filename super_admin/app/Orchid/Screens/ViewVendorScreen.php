<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewVendorLayout;

class ViewVendorScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'vendors' => Vendors::latest('vendors.created_at')->filter(request(['country', 'category_id', 'state_province']))->where('vendors.account_status', 1)->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Vendors';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Link::make('Add New Vendors')
                ->icon('plus')
                ->route('platform.vendor.create'),

            Button::make('Delete Selected Vendors')
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

            ViewVendorLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/vendors?' 
                    .'&country=' . $request->get('country')
                    .'&category_id=' . $request->get('category_id')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function deletevendors(Request $request){  

        //get all vendors from post request
        $vendors = $request->get('vendors');
        
        try{

            //if the array is not empty
            if(!empty($vendors)){

                //loop through the vendors and delete them from db
                foreach($vendors as $vendor_id){
                    Vendors::where('user_id', $vendor_id)->delete();
                    User::where('id', $vendor_id)->delete();
                }

                Toast::success('Selected vendors deleted succesfully');

            }else{
                Toast::warning('Please select vendors in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected vendors. Error Message: ' . $e);
        }
    }
}
