<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Screen;

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
