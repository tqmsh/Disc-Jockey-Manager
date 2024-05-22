<?php

namespace App\Orchid\Screens;

use App\Models\Guide;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewGuideLayout;
use Exception;
use Orchid\Support\Facades\Toast;

class ViewGuideScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'guides' => Guide::orderBy('ordering', 'asc')->paginate(request()->query('pagesize', 10)),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Guides';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Delete Selected Guides')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete the selected guides?'),
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
            ViewGuideLayout::class,

            Layout::rows([

                Input::make('guide_name')
                ->title('Guide Name')
                ->placeholder('Enter the name of the guide'),

                Select::make('category')
                ->title('Category')
                ->options([
                    2 => 'Committee',
                    3 => 'Student',
                    4 => 'Vendor',
                ]),

                Input::make('ordering')
                ->title('Ordering')
                ->placeholder('Enter the ordering of the guide'),

                Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createGuide'),
            ])->title('Add a Guide'),
        ];
    }

    public function createGuide(){

        try{
            $fields = request()->validate([
                'ordering' => 'required|numeric',
                'guide_name' => 'required',
                'category' => 'required',
            ]);

            if(is_null($fields['guide_name']) || is_null($fields['ordering'])){

                Toast::error('Guide name and ordering cannot be empty');

            }else if(!empty(Guide::where('guide_name',  $fields['guide_name'])->first()) || !empty(Guide::where('ordering',  $fields['ordering'])->first())){

                Toast::error('Guide or ordering already exists');

            }else{

                $check = Guide::create($fields);

                if($check){

                    Toast::success('Guide created successfully');

                }else{

                    Toast::error('Guide could not be created for an unknown reason');
                }
            }
        }catch(Exception $e){
            Toast::error($e->getMessage());
        }
    }

    public function redirect( $guide, $type){

        if($type == "edit"){
            return redirect()->route('platform.guide.edit',  $guide);
        }
        else if($type == "section"){
            return redirect()->route('platform.guideSection.list',  $guide);
        }
    }

    public function delete(){

        //get all guides from post request
        $guides = request('guides');

        try{

            //if the array is not empty
            if(!empty($guides)){

                Guide::whereIn('id', $guides)->delete();

                Toast::success('Selected guides deleted succesfully');

            }else{
                Toast::warning('Please select guides in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected guides. Error Message: ' . $e->getMessage());
        }
    }
}
