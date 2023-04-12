<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Course;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditCourseScreen extends Screen
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
            'course' => $course
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Course: ' . $this->course->course_name;
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

            Button::make('Delete Course')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this course?'),
            
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.course.list'),
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
                Input::make('ordering')
                    ->title('Ordering')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->course->ordering),
                    
                Input::make('course_name')
                    ->title('Course Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->course->course_name),

                Select::make('category')
                ->title('Category')
                ->horizontal()
                ->required()
                ->options([
                    2 => 'Committee',
                    3 => 'Student',
                    4 => 'Vendor',
                ]),
            ])
        ];
    }

    public function update(Course $course)
    {
        
        try{
            $fields = request()->validate([
                'ordering' => 'required',
                'course_name' => 'required',
                'category' => 'required',
            ]);


            if ( ! empty( Course::where( 'course_name', $fields['course_name'] )->whereNot('id', $course->id)->first() ) 
                    || ! empty( Course::where( 'ordering', $fields['ordering'] )->whereNot('id', $course->id)->first() ) 
                ) {
                Toast::error( 'Course or ordering already exists' );
            } else {
                $course->update( $fields );
                Toast::success( 'Course updated successfully' );
                return redirect()->route( 'platform.course.list' );
            }

        }catch(Exception $e){
            return redirect()->route('platform.course.list' . $e->getMessage());
        }
    }

    public function delete(Course $course)
    {
        try{
            $course->delete();
            Toast::success('Course deleted successfully');
            return redirect()->route('platform.course.list');
        }catch(Exception $e){
            Toast::error('Error deleting course' . $e->getMessage());
            return redirect()->route('platform.course.list');
        }
    }
}
