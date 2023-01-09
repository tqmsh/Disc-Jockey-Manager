<?php

namespace App\Orchid\Screens;

use App\Models\Region;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewRegionLayout;
use Exception;

class ViewRegionScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'regions' => Region::latest()->paginate(8),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Regions';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Delete Selected Regions')
                ->icon('trash')
                ->method('deleteRegions')
                ->confirm(__('Are you sure you want to delete the selected regions?')),
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

            ViewRegionLayout::class,

            Layout::rows([

                Input::make('region_name')
                    ->title('Region Name')
                    ->placeholder('Enter the name of the region'),
                    
                Button::make('Add')
                    ->icon('plus')
                    ->type(Color::DEFAULT())
                    ->method('createRegion'),
            ])
        ];
    }

    //this method will create the region
    public function createRegion()
    {
        //take region from request then check for duplicate
        $region = request('region_name');

        if(is_null($region)){
            
            Toast::error('Region name cannot be empty');

        }else if(!empty(Region::where('name', $region)->first())){
            
            Toast::error('Category already exists');
            
        }else{

            //update the region if it already exists or create it if it doesnt
            $check = Region::create(['name' => $region]);

            if($check){

                Toast::success('Region named: ' . $region . ' created successfully');

            }else{

                Toast::error('Region could not be created for an unknown reason');
            }
        }
    }

    public function deleteRegions(Request $request)
    {   
        //get all regions from post request
        $regions = $request->get('regions');
        
        try{
            //if the array is not empty
            if(!empty($regions)){

                //loop through the regions and delete them from db
                foreach($regions as $region){
                    Region::where('id', $region)->delete();
                }

                Toast::success('Selected regions deleted succesfully');

            }else{
                Toast::warning('Please select regions in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected regions. Error Message: ' . $e->getMessage());
        }
    }
}
