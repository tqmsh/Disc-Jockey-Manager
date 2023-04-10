<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditCourseSectionScreen extends Screen
{
    public $course;
    public $section;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Course $course, Section $section): iterable
    {
        return [
            'course' => $course,
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
        return 'Edit Section: ' . $this->section->section_name;
    }

    public function description(): ?string
    {
        return 'Course: ' . $this->course->course_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Update')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Section')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this section?'),
            
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.courseSection.list', ['course' => $this->course]),
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
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->section->ordering),
                    
                Input::make('section_name')
                    ->title('Section Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->section->section_name),
            ])
        ];
    }

    public function update(Course $course, Section $section)
    {
        try {

            $fields = request()->validate([
                'ordering' => 'required|numeric',
                'section_name' => 'required',
            ]);

            if(Section::where('ordering',$fields['ordering'])->where('course_id', $course->id)->whereNot('id',$section->id)->exists()) {

                Toast::error('Ordering already exists');
                return redirect()->route('platform.courseSection.edit', ['course' => $course, 'section' => $section]);

            } else if(Section::where('section_name', $fields['section_name'])->where('course_id', $course->id)->whereNot('id',$section->id)->exists()) {

                Toast::error('Section name already exists');
                return redirect()->route('platform.courseSection.edit', ['course' => $course, 'section' => $section]);
            }

            $section->update([
                'ordering' =>$fields['ordering'],
                'section_name' => $fields['section_name'],
            ]);
        } catch (\Exception $e) {
            Toast::error('Something went wrong');
            return redirect()->route('platform.courseSection.edit', ['course' => $course, 'section' => $section]);
        }

        Toast::success('Section updated successfully.');

        return redirect()->route('platform.courseSection.list', ['course' => $course]);
    }

    public function delete(Course $course, Section $section)
    {
        try {
            $section->delete();
        } catch (\Exception $e) {
            Toast::error('Something went wrong');
            return redirect()->route('platform.courseSection.edit', ['course' => $course, 'section' => $section]);
        }

        Toast::success('Section deleted successfully.');

        return redirect()->route('platform.courseSection.list', ['course' => $course]);
    }
}
