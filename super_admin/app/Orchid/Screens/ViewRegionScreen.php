<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Region;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\ViewRegionLayout;

class ViewRegionScreen extends Screen
{
    public $requiredFields = ['name'];
    public $dupes =[];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'regions' => Region::latest()->paginate(10),
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

            ModalToggle::make('Mass Import Regions')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),
                
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

            Layout::modal('massImportModal',[

                Layout::rows([

                    Input::make('regions_csv')
                        ->type('file')
                        ->title('File must be in csv format. Ex. regions.csv')
                        ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the regions: <br>
                            • name <br>'),
                    Link::make('Download Sample CSV')
                        ->icon('download')
                        ->href('/sample_regions_upload.csv')
                ]),
            ])
            ->title('Mass Import Regions')
            ->applyButton('Import')
            ->withoutCloseButton(),

            
            Layout::rows([
                
                Input::make('region_name')
                ->title('Region Name')
                ->placeholder('Enter the name of the region'),
                
                Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createRegion'),
            ]),

            ViewRegionLayout::class,
        ];
    }

    //this method will mass import schools from a csv file
    public function massImport(Request $request){

        try{

            $path = $this->validFile($request);

            if($path){

                $regions = $this->csvToArray($path);

                $keys = array_keys($regions[0]);

                //check if the user has the required values in the csv file
                foreach($this->requiredFields as $field){

                    if(!in_array($field, $keys)){
                        Toast::error('"' . $field . '"' . 'is missing in your csv file.'); return;
                    }
                }

                //loop through the array of regions and re-write the keys to insert in db
                for ($i = 0; $i < count($regions); $i ++){
                    
                    //check for duplicate regions
                    if(count(Region::where('name', $regions[$i]['name'])->get()) == 0){
                        
                        Region::create(['name' => $regions[$i]['name']]);

                    }else{
                        array_push($this->dupes, $regions[$i]['name']);                    
                    }
                }

                if(!empty($this->dupes)){
                    $message = 'The following regions have not been added as they already exist: ';

                    foreach($this->dupes as $region){

                        $message .="| " . $region . " | ";
                    }

                    Alert::error($message);
                }else{

                    Toast::success('Regions imported successfully!');
                }


                return redirect()->route('platform.region.list');
            }
        }catch(Exception $e){
            
            Alert::error('There was an error mass importing the regions. Error Code: ' . $e->getMessage());
        }
    }

    //this method will create the region
    public function createRegion()
    {
        //take region from request then check for duplicate
        $region = request('region_name');

        if(is_null($region)){
            
            Toast::error('Region name cannot be empty');

        }else if(!empty(Region::where('name', $region)->first())){
            
            Toast::error('Region already exists');
            
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

    public function redirect($region){
        return redirect()-> route('platform.region.edit', $region);
    }

    public function deleteRegions(Request $request)
    {   
        //get all regions from post request
        $regions = $request->get('regions');
        
        try{
            //if the array is not empty
            if(!empty($regions)){
                
                //delete all regions in the array
                Region::whereIn('id', $regions)->delete();
                
                Toast::success('Selected regions deleted succesfully');

            }else{
                Toast::warning('Please select regions in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected regions. Error Message: ' . $e->getMessage());
        }
    }

    private function validFile(Request $request){

        $path = '';

        if(!is_null($request->file('regions_csv'))){

            $path = $request->file('regions_csv')->getRealPath();

            if(!is_null($path)){

                $extension = $request->file('regions_csv')->extension();

                if($extension != 'csv' && $extension != 'txt'){

                    Toast::error('Incorrect file type.'); return false;
                }else{

                    return $path;
                }

            } else{
                
                Toast::error('An error has occured.'); return;
            }

        } else{

            Toast::error('Upload a csv file to import regions.'); return false;
        }
    }

    //this function will convert the csv file to an array
    private function csvToArray($filename = '', $delimiter = ','){
        
        if (!file_exists($filename) || !is_readable($filename)){
            Alert::error('There has been an error finding this file.');
            return;
        }

        $header = null;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== false){

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }

            fclose($handle);
        }

        return $data;
    }
}
