<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Guide;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewGuideSectionLayout;

class ViewGuideSectionScreen extends Screen
{
    public $guide;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Guide $guide): iterable
    {
        return [
            'guide' => $guide,
            'sections' => $guide->sections()->orderBy('ordering', 'asc')->paginate(min(request()->query('pagesize', 10), 100)),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Sections for Guide: ' . $this->guide->guide_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Delete Selected Sections')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete the selected sections?'),
            
            Link::make('Back to Guide List')
                ->route('platform.guide.list')
                ->icon('arrow-left')
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
            ViewGuideSectionLayout::class,

            Layout::rows([
                
                Input::make('section_name')
                ->title('Section Name')
                ->placeholder('Enter the name of the section'),

                Input::make('ordering')
                ->title('Ordering')
                ->placeholder('Enter the ordering of the section'),

                Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createSection'),

            ])->title('Add a Section'),
        ];
        
    }

    public function redirect(Guide $guide){
        if(request('type') == "lesson"){
            return redirect()->route('platform.sectionLesson.list',  ['guide' => $guide, 'section' => request('section_id')]);
        }
        else if(request('type') == "edit"){
            return redirect()->route('platform.guideSection.edit',  ['guide' => $guide, 'section' => request('section_id')]);
        }
    }

    public function createSection(Guide $guide){

        try{
            
            $fields = request()->validate([
                'section_name' => 'required',
                'ordering' => 'required|numeric',
            ]);

            if($guide->sections()->where('ordering', $fields['ordering'])->exists()){
                throw new Exception('Ordering already exists');

            } else if($guide->sections()->where('section_name', $fields['section_name'])->exists()){
                throw new Exception('Section name already exists');

            } else{
                $guide->sections()->create([
                    'section_name' => $fields['section_name'],
                    'ordering' => $fields['ordering'],
                ]);

                Toast::success('Section added successfully');
            }
        }catch(Exception $e){
            Toast::error($e->getMessage());
        }


        return redirect()->route('platform.guideSection.list', $guide);
    }

    public function delete(){

        //get all guides from post request
        $sections = request('sections');

        
        try{

            //if the array is not empty
            if(!empty($sections)){

                Section::whereIn('id', $sections)->delete();

                Toast::success('Selected sections deleted succesfully');

            }else{
                Toast::warning('Please select sections in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected sections. Error Message: ' . $e->getMessage());
        }
    }


}
