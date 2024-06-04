<?php

namespace App\Orchid\Screens;

use App\Models\Guide;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewGuideSectionLayout;

class ViewGuideSectionScreen extends Screen
{
    public $guide;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Guide $guide): iterable
    {
        return [
            'guide' => $guide,
            'sections' => $guide->sections()->orderBy('ordering', 'asc')->paginate(request()->query('pagesize', 20)),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Sections for Guide: ' . $this->guide->guide_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            
            Link::make('Back to Guide List')
                ->route('platform.guide.list')
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
            ViewGuideSectionLayout::class,
        ];
        
    }

    public function redirect(Guide $guide){
        if(request('type') == "lesson"){
            return redirect()->route('platform.sectionLesson.list',  ['guide' => $guide, 'section' => request('section_id')]);
        }
    }
}
