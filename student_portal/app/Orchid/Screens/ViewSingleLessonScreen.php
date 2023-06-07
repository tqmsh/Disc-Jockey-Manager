<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Orchid\Screen\Sight;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class ViewSingleLessonScreen extends Screen
{
    public $course;
    public $lesson;
    public $section;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Course $course, Section $section, Lesson $lesson): iterable
    {
        abort_if($course->category != 3, 403, 'You are not authorized to view this page.');
        return [
            'course' => $course,
            'section' => $section,
            'lesson' => $lesson,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View Lesson: ' . $this->lesson->lesson_name;
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
            Link::make('Back to Section Lessons')
                ->icon('arrow-left')
                ->route('platform.sectionLesson.list', [
                    'course' => $this->course->id,
                    'section' => $this->section->id,
                ]),
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
            Layout::legend('lesson',[
                Sight::make('ordering', 'Order'),
                Sight::make('lesson_name', 'Lesson Name'),
                Sight::make('lesson_description', 'Lesson Description'),
                Sight::make('lesson_content', 'Lesson Content'),
                Sight::make('created_at', 'Created At')->render(function(){
                    return date_format($this->lesson->created_at, 'd/m/Y H:i:s');
                }),
                Sight::make('updated_at', 'Updated At')->render(function(){
                    return ($this->lesson->updated_at == null) ? '' : date_format($this->lesson->updated_at, 'd/m/Y H:i:s');
                }),
            ])->title('Lesson Information'),
        ];
    }
}
