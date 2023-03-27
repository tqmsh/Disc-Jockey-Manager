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

                Select::make('type')
                ->title('Category')
                ->options([
                    2 => 'Committee',
                    3 => 'Student',
                    4 => 'Vendor',
                ]),

                
                Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createCategory'),
            ])->title('Add Course'),
        ];
    }
    
    public function redirect($course){
        return redirect()-> route('platform.course.edit', $course);
    }
}
