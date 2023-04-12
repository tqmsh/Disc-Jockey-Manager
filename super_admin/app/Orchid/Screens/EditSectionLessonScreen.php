<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;

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
        return [
            Button::make('Save')
                ->icon('check')
                ->method('saveLesson'),

            Button::make('Delete')
                ->icon('trash')
                ->method('deleteLesson')
                ->confirm('Are you sure you want to delete this lesson?'),

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
                ->placeholder('Enter the ordering of the lesson')
                ->value($this->lesson->ordering),
                
                Input::make('lesson_name')
                ->title('Lesson Name')
                ->placeholder('Enter the name of the lesson')
                ->value($this->lesson->lesson_name),

                TextArea::make('lesson_description')
                    ->title('Lesson Description')
                    ->placeholder('Enter a short description of the lesson')
                    ->rows(3)
                    ->value($this->lesson->lesson_description),

                TextArea::make('lesson_content')
                    ->title('Lesson Content')
                    ->placeholder('Enter the lesson content')
                    ->rows(50)
                    ->value($this->lesson->lesson_content),

            ])->title('Lesson Details'),
        ];
    }

    public function saveLesson(Course $course, Section $section, Lesson $lesson)
    {
        try {

            $fields = request()->validate([
                'ordering' => 'required|numeric',
                'lesson_name' => 'required|string',
                'lesson_description' => 'required|string',
                'lesson_content' => 'required|string',
            ]);

            if(Lesson::where('section_id', $section->id)->where('ordering', $fields['ordering'])->whereNot('id', $lesson->id)->exists()){
                Toast::error('There is already a lesson with that ordering');

            } else if(Lesson::where('section_id', $section->id)->where('lesson_name', $fields['lesson_name'])->whereNot('id', $lesson->id)->exists()){
                Toast::error('There is already a lesson with that name');

            } else {
                $lesson->update($fields);
                Toast::success('Lesson updated successfully');

                return redirect()->route('platform.sectionLesson.list', [
                    'course' => $course->id,
                    'section' => $section->id,
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->route('platform.sectionLesson.list', [
                'course' => $course->id,
                'section' => $section->id,
            ])->with('error', 'There was an error updating the lesson');
        }
    }

    public function deleteLesson(Course $course, Section $section, Lesson $lesson)
    {
        try {
            $lesson->delete();
            Toast::success('Lesson deleted successfully');
            return redirect()->route('platform.sectionLesson.list', [
                'course' => $course->id,
                'section' => $section->id,
            ]);
        } catch (\Exception $e) {
            Toast::error('Error deleting lesson');
            return redirect()->route('platform.sectionLesson.list', [
                'course' => $course->id,
                'section' => $section->id,
            ]);
        }
    }
}
