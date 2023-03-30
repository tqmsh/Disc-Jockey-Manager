<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ViewCourseSectionLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;

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
        return 'Section for Course: ' . $this->course->course_name;
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
        ];
        
    }

    public function redirect($section, $type){

        if($type == "edit"){
            return redirect()->route('platform.courseSection.edit',  $section);
        }
        else if($type == "lesson"){
            return redirect()->route('platform.sectionLesson.list',  [$this->course, $section]);
        }

    }

}
