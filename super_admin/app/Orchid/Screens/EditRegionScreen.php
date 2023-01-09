<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Region;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditRegionScreen extends Screen
{

    public $region;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Region $region): iterable
    {
        return [
            'region' => $region

        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: ' . $this->region->name;
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

            Button::make('Delete Region')
                ->icon('trash')
                ->method('delete'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.region.list')
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

                Input::make('region_name')
                    ->title('Region Name')
                    ->placeholder('Enter the name of the region')
                    ->help('This is the name of the category that will be displayed to the user.')
                    ->value($this->region->name),
            ])
        ];
    }

    public function update(Region $region){

        try{

            $region->name = request('region_name');
    
            $region->save();
    
            Toast::success('Region updated successfully.');
    
            return redirect()->route('platform.region.list');

        }catch(Exception $e){
            Alert::error($e->getMessage());
        }
    }

    public function delete(Region $region)
    {
        try{
            
            $region->delete();

            Toast::info('You have successfully deleted the region.');
    
            return redirect()->route('platform.region.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this region. Error Code: ' . $e);
        }
    }
}
