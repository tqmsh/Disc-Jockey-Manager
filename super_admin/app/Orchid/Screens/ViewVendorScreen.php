<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\Region;
use App\Models\VendorPaidRegions;
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
                        ->empty('No Selection')
                        ->help('Type in boxes to search')
                        ->fromModel(Vendors::class, 'country', 'country'),

                    Select::make('category_id')
                        ->title('Category')
                        ->empty('No Selection')
                        ->fromQuery(Categories::query(), 'name')
                        ->placeholder('Select Category'),
                    
                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No Selection')
                        ->fromModel(Vendors::class, 'state_province', 'state_province'),

                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewVendorLayout::class,

            Layout::rows([
                Select::make('user_ids')
                    ->title('Vendors')
                    ->empty('No Selection')
                    ->fromModel(Vendors::class, 'company_name', 'user_id')
                    ->multiple()
                    ->help('Select the vendors you want to add paid regions to')
                    ->placeholder('Start typing to search...'),

                Select::make('region_ids')
                    ->title('Paid Region')
                    ->empty('No Selection')
                    ->fromModel(Region::class, 'name', 'id')
                    ->multiple()
                    ->help('Select the paid regions you want to add to the vendors')
                    ->placeholder('Start typing to search...'),

                Button::make('Add Paid Regions')
                    ->icon('plus')
                    ->method('addPaidRegions')
                    ->type(Color::DEFAULT())

            ])->title('Add Paid Regions to Vendors')
        ];
    }

    public function addPaidRegions(Request $request){

        //get all vendors from post request
        $vendor_ids = $request->get('user_ids');

        //get all regions from post request
        $region_ids = $request->get('region_ids');

        try{

            //if the array is not empty
            if(!empty($vendor_ids) && !empty($region_ids)){

                //loop through the vendors and add the paid regions to them
                foreach($vendor_ids as $vendor_id){
                    foreach($region_ids as $region_id){

                        VendorPaidRegions::firstOrCreate([
                            'user_id' => $vendor_id,
                            'region_id' => $region_id
                        ]);
                    }
                }

                Toast::success('Paid regions added to selected vendors succesfully');

            }else{
                Toast::warning('Please select vendors and paid regions in order to add them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to add the paid regions to the selected vendors. Error Message: ' . $e);
        }
    }

    public function filter(){

        return redirect()->route('platform.vendor.list', request(['country', 'category_id', 'state_province']));
    }

    public function redirect($vendor){
        return redirect()-> route('platform.vendor.edit', $vendor);
    }

    public function deleteVendors(Request $request){  

        //get all vendors from post request
        $vendor_ids = $request->get('vendors');
        
        try{

            //if the array is not empty
            if(!empty($vendor_ids)){

                //loop through the vendors and delete them from db
                foreach($vendor_ids as $vendor_id){
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
