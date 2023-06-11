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

class CreatePackageScreen extends Screen
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
        return 'Create a Package';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Package')
                ->icon('plus')
                ->method('createPackage'),

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
                    ->help('Enter the name of your package.'),

                Input::make('price')
                    ->title('Package Price - $USD')
                    ->placeholder('Enter your package price')
                    ->type('number')
                    ->required()
                    ->help('Enter the price of your package.'),

                TextArea::make('description')
                    ->title('Description')
                    ->placeholder('Enter your package description')
                    ->rows(5)
                    ->help('Enter a description you would like to include with your package.'),

                Input::make('url')
                    ->title('URL for Package')
                    ->placeholder('https://promplanner.app/product/lifetime-vendor-license/')
                    ->type('url')
                    ->help('Enter the url that leads to your package.'),

            ])->title('Make the Perfect Package!')
        ];
    }

    public function createPackage(Request $request){

        try{
            
            if($this->validPackage($request)){

                $input = $request->all();
    
                $input['user_id'] = Auth::user()->id;

                $package = VendorPackage::create($input);

                if($package){
                    Toast::success('Package Created!');
                    return redirect()->route('platform.package.list');
                }else{
                    Alert::error('Error: Package not created for an unkown reason.');
                }
            }else{
                Toast::error('Package name already exists.');
            }

        } catch (Exception $e) {
            Alert::error('Error: ' . $e->getMessage());
        }
    }

    private function validPackage(Request $request){

        return count(VendorPackage::where('user_id', Auth::user()->id)->where('package_name', $request->package_name)->get()) == 0;
    }
}
