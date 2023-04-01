<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Course;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewCourseSectionLayout;

class ViewCourseSectionScreen extends Screen
{
    public $course;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Course $course): iterable
    {
        return [
            'course' => $course,
            'sections' => $course->sections()->orderBy('ordering', 'asc')->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Sections for Course: ' . $this->course->course_name;
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
            
            Link::make('Back to Course List')
                ->route('platform.course.list')
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
            ViewCourseSectionLayout::class,

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

    public function redirect(Course $course){
        if(request('type') == "lesson"){
            return redirect()->route('platform.sectionLesson.list',  ['course' => $course, 'section' => request('section_id')]);
        }
        else if(request('type') == "edit"){
            return redirect()->route('platform.courseSection.edit',  ['course' => $course, 'section' => request('section_id')]);
        }
    }

    public function createSection(Course $course){

        try{
            
            $fields = request()->validate([
                'section_name' => 'required',
                'ordering' => 'required',
            ]);

            if($course->sections()->where('ordering', $fields['ordering'])->exists()){
                throw new Exception('Ordering already exists');

            } else if($course->sections()->where('section_name', $fields['section_name'])->exists()){
                throw new Exception('Section name already exists');

            } else{
                $course->sections()->create([
                    'section_name' => $fields['section_name'],
                    'ordering' => $fields['ordering'],
                ]);

                Toast::success('Section added successfully');
            }
        }catch(Exception $e){
            Toast::error($e->getMessage());
        }


        return redirect()->route('platform.courseSection.list', $course);
    }

    public function delete(){

        //get all courses from post request
        $sections = request('sections');

        
        try{

            //if the array is not empty
            if(!empty($sections)){

                //loop through the courses and delete them from db
                foreach($sections as $section_id){
                    Section::find($section_id)->delete();
                }

                Toast::success('Selected sections deleted succesfully');

            }else{
                Toast::warning('Please select sections in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected sections. Error Message: ' . $e->getMessage());
        }
    }


}
