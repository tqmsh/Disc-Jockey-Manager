<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Orchid\Screen\Screen;

class EditSectionLessonScreen extends Screen
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
        return 'Edit Lesson: ' . $this->lesson->lesson_name;
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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
