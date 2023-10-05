<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class EditPackageScreen extends Screen
{
    public $package;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(VendorPackage $package): iterable
    {
        abort_if($package->user_id != Auth::user()->id, 403, 'You are not authorized to view this page.');
        return [
            'package' => $package
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Package';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Package')
                ->icon('trash')
                ->method('delete'),

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
           Layout::rows([

                Input::make('package_name')
                    ->title('Package Name')
                    ->placeholder('Enter your package name')
                    ->required()
                    ->help('Enter the name of your package.')
                    ->value($this->package->package_name),

                Input::make('price')
                    ->title('Package Price - $USD')
                    ->placeholder('Enter your package price')
                    ->type('number')
                    ->required()
                    ->help('Enter the price of your package.')
                    ->value($this->package->price),

                TextArea::make('description')
                    ->title('Description')
                    ->placeholder('Enter your package description')
                    ->rows(5)
                    ->help('Enter a description you would like to include with your package.')
                    ->value($this->package->description),

                Input::make('url')
                    ->title('URL for Package')
                    ->placeholder('https://promplanner.app/product/lifetime-vendor-license/')
                    ->type('url')
                    ->help('Enter the url that leads to your package.')
                    ->value($this->package->url),

            ])->title($this->package->package_name)
        ];
    }

    public function update(VendorPackage $package, Request $request){

        try{
            
            if($this->validPackage($request, $package->id)){

                $package = $package->fill($request->all())->save();

                if($package){
                    Toast::success('Package updated successfully!');
                    return redirect()->route('platform.package.list');
                }else{
                    Alert::error('Error: Package not updated for an unknown reason.');
                }
            }else{
                Toast::error('Package name already exists.');
            }

        } catch (Exception $e) {
            Alert::error('Error: ' . $e->getMessage());
        }
    }

    public function delete(VendorPackage $package)
    {
        try{
            
            $package->delete();

            Toast::info('You have successfully deleted the package.');
    
            return redirect()->route('platform.package.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this package. Error Code: ' . $e->getMessage());
        }
    }

    private function validPackage(Request $request, $current_package_id){

        return count(VendorPackage::where('user_id', Auth::user()->id)->where('package_name', $request->package_name)->whereNot('id', $current_package_id)->get()) == 0;
    }
}
