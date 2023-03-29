<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewCourseLayout;
use Orchid\Support\Facades\Toast;

use function PHPSTORM_META\type;

class ViewCourseScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'courses' => Course::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Courses';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewCourseLayout::class,

            Layout::rows([
                
                Input::make('course_name')
                ->title('Course Name')
                ->placeholder('Enter the name of the course'),

                Select::make('category')
                ->title('Category')
                ->options([
                    2 => 'Committee',
                    3 => 'Student',
                    4 => 'Vendor',
                ]),

                Input::make('ordering')
                ->title('Ordering')
                ->placeholder('Enter the ordering of the course'),

                Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createCourse'),
            ])->title('Add Course'),
        ];
    }

    public function createCourse(){

        $fields = request()->all();

        if(is_null($fields['course_name']) || is_null($fields['ordering'])){
            
            Toast::error('Course name and ordering cannot be empty');

        }else if(!empty(Course::where('course_name',  $fields['course_name'])->first()) || !empty(Course::where('ordering',  $fields['ordering'])->first())){
            
            Toast::error('Course or ordering already exists');
            
        }else{

            $check = Course::create($fields);

            if($check){

                Toast::success('Course created successfully');

            }else{

                Toast::error('Course could not be created for an unknown reason');
            }
        }
    }
    
    public function redirect( $course_name, $type){

        if($type == "edit"){
            return redirect()-> route('platform.course.edit',  $course_name);
        }
        else if($type == "section"){
            return redirect()-> route('platform.courseSection.list',  $course_name);
        }
    }
}
