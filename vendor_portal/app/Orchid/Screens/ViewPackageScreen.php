<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewPackageLayout;

class ViewPackageScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'packages' => Auth::user()->packages
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Your Packages';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Link::make('Create a New Package')
                ->icon('plus')
                ->route('platform.package.create'),

            Button::make('Delete Selected Packages')
                ->icon('trash')
                ->method('deletePackages')
                ->confirm(__('Are you sure you want to delete the selected packages?')),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.package.list')
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
            ViewPackageLayout::class
        ];
    }

    public function redirect($package_id){
        return redirect()->route('platform.package.edit', $package_id);
    }

    public function deletePackages(Request $request)
    {   
        //get all packages from post request
        $packages = $request->get('vendorPackages');
        
        try{
            //if the array is not empty
            if(!empty($packages)){

                //loop through the packages and delete them from db
                foreach($packages as $package){
                    VendorPackage::where('id', $package)->delete();
                }

                Toast::success('Selected packages deleted succesfully');

            }else{
                Toast::warning('Please select packages in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected packages. Error Message: ' . $e->getMessage());
        }
    }
}
