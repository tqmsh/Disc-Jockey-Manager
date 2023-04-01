<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;

class CreateSectionLessonScreen extends Screen
{
    public $course;
    public $section;
    public $lesson;
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
        return 'Create a new lesson';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create')
                ->icon('check')
                ->method('create'),

            Link::make('Cancel')
                ->icon('close')
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
            Layout::rows([
                
                Input::make('ordering')
                ->title('Ordering')
                ->placeholder('Enter the ordering of the lesson'),
                
                Input::make('lesson_name')
                ->title('Lesson Name')
                ->placeholder('Enter the name of the lesson'),

                

            ])->title('Lesson Details'),
        ];
    }
}
