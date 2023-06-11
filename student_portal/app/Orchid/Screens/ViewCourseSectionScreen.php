<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;

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
        abort_if($course->category != 3, 403, 'You are not authorized to view this page.');
        return [
            'course' => $course,
            'sections' => $course->sections()->orderBy('ordering', 'asc')->paginate(20),
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

    public function redirect(Course $course){
        if(request('type') == "lesson"){
            return redirect()->route('platform.sectionLesson.list',  ['course' => $course, 'section' => request('section_id')]);
        }
    }
}
