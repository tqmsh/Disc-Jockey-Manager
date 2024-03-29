<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Guide;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateSectionLessonScreen extends Screen
{
    public $guide;
    public $section;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Guide $guide, Section $section): iterable
    {
        return [
            'guide' => $guide,
            'section' => $section,
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
                ->method('createLesson'),

            Link::make('Cancel')
                ->icon('close')
                ->route('platform.sectionLesson.list', [
                    'guide' => $this->guide->id,
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

                TextArea::make('lesson_description')
                    ->title('Lesson Description')
                    ->placeholder('Enter a short description of the lesson')
                    ->rows(3),

                TextArea::make('lesson_content')
                    ->title('Lesson Content')
                    ->placeholder('Enter the lesson content')
                    ->rows(50)

            ])->title('Lesson Details'),
        ];
    }

    public function createLesson(Guide $guide, Section $section)
    {
        $fields = request()->validate([
            'ordering' => 'required|numeric',
            'lesson_name' => 'required',
            'lesson_description' => 'required',
            'lesson_content' => 'required',
        ]);

        $fields['section_id'] = $section->id;
        $fields['guide_id'] = $guide->id;

        try {
            if($section->lessons()->where('ordering', $fields['ordering'])->exists()) {
                throw new Exception('Ordering already exists');
            } else if($section->lessons()->where('lesson_name', $fields['lesson_name'])->exists()) {
                    throw new Exception('Section name already exists');
            } else {
                $section->lessons()->create($fields);

                Toast::success('Lesson added successfully');
                return redirect()->route('platform.sectionLesson.list',  ['guide' => $guide, 'section' => $section]);
            }
        } catch(Exception $e) {
            Toast::error($e->getMessage());
        }

    }
}
