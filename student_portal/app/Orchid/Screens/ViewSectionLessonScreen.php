<?php

namespace App\Orchid\Screens;

use App\Models\Guide;
use App\Models\Section;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
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
        abort_if($guide->category != 3, 403, 'You are not authorized to view this page.');
        return [
            'guide' => $guide,
            'section' => $section,
            'lessons' => $section->lessons()->orderBy('ordering', 'asc')->paginate(request()->query('pagesize', 20)),
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
    }
}
