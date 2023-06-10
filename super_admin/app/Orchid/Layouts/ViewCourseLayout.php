<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Course;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewCourseLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'courses';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
          TD::make()
                ->render(function (Course $course){
                    return CheckBox::make('courses[]')
                        ->value($course->id)
                        ->checked(false);
                }),

            TD::make('ordering', 'Order')
                ->render(function (Course $course) {
                    return Link::make($course->ordering)
                        ->route('platform.courseSection.list', $course);
                }),
            TD::make('course_name', 'Course Name')
                ->render(function (Course $course) {
                    return Link::make($course->course_name)
                        ->route('platform.courseSection.list', $course);
                }),
            TD::make('category', 'Category')
                ->render(function (Course $course) {
                    return 
                        ($course->category == 2) ? 'Committee' 
                        : (($course->category == 3) ? 'Student' 
                        : (($course->category == 4) ? 'Vendor' 
                        : 'Other'));                   

                }),

            TD::make('created_at', 'Created At')
                ->render(function (Course $course) {
                    return Link::make($course->created_at)
                        ->route('platform.courseSection.list', $course);
                }),

            TD::make('updated_at', 'Updated At')
                ->render(function (Course $course) {
                    return Link::make($course->updated_at)
                        ->route('platform.courseSection.list', $course);
                }),

            TD::make()
                ->render(function (Course $course) {
                    return Button::make('Sections')-> type(Color::SUCCESS())->method('redirect', ['course'=> $course->id, 'type' => 'section'])->icon('layers');
                }),
            TD::make()
                ->render(function (Course $course) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->method('redirect', ['course'=> $course->id, 'type' => 'edit'])->icon('pencil');
                }),
        ];
    }
}
