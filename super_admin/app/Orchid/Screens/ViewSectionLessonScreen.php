<?php

namespace App\Orchid\Screens;

use App\Models\Guide;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\ViewSectionLessonLayout;

class ViewSectionLessonScreen extends Screen
{
    public $guide;
    public $section;
    public $lessons;
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
            'lessons' => $section->lessons()->orderBy('ordering', 'asc')->paginate(10),
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
        return 'Guide: ' . $this->guide->guide_name . ' | Section: ' . $this->section->section_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Link::make('Add New Lesson')
                ->icon('plus')
                ->route('platform.sectionLesson.create', ['guide' => $this->guide, 'section' => $this->section]),

            Button::make('Delete Selected Lessons')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete the selected lessons?'),
            
            Link::make('Back to Section List')
                ->route('platform.guideSection.list', ['guide' => $this->guide])
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

    public function redirect(Guide $guide, Section $section){
        if(request('type') == "view"){
            return redirect()->route('platform.singleLesson.list',  ['guide' => $guide, 'section' => $section, 'lesson' => request('lesson_id')]);
        }
        else if(request('type') == "edit"){
            return redirect()->route('platform.sectionLesson.edit',  ['guide' => $guide, 'section' => $section, 'lesson' => request('lesson_id')]);
        }
    }

    public function delete(Guide $guide, Section $section){
        $lessons = request('lessons');
        if($lessons){
            foreach($lessons as $lesson){
                $lesson = $section->lessons()->where('id', $lesson)->first();
                $lesson->delete();
            }
            Toast::success('Lessons deleted successfully');
        }
        else{
            Toast::info('No lessons selected');
        }
        return redirect()->route('platform.sectionLesson.list', ['guide' => $guide, 'section' => $section]);
    }
}
