<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewSectionLessonLayout;

class ViewSectionLessonScreen extends Screen
{
    public $course;
    public $section;
    public $lessons;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Course $course, Section $section): iterable
    {
        abort_if($course->category != 4, 403, 'You are not authorized to view this page.');
        return [
            'course' => $course,
            'section' => $section,
            'lessons' => $section->lessons()->orderBy('ordering', 'asc')->paginate(20),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Lessons for Section: ' . $this->section->section_name;
    }

    public function description(): ?string
    {
        return 'Course: ' . $this->course->course_name . ' | Section: ' . $this->section->section_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            
            Link::make('Back to Section List')
                ->route('platform.courseSection.list', ['course' => $this->course])
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
            ViewSectionLessonLayout::class,
        ];
    }

    public function redirect(Course $course, Section $section){
        if(request('type') == "view"){
            return redirect()->route('platform.singleLesson.list',  ['course' => $course, 'section' => $section, 'lesson' => request('lesson_id')]);
        }
    }
}
