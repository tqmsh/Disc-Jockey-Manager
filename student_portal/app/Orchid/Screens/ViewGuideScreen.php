<?php

namespace App\Orchid\Screens;

use App\Models\Guide;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ViewGuideLayout;

class ViewGuideScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'guides' => Guide::orderBy('ordering', 'asc')->where('category', 3)->paginate(20),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Guides';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

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
            ViewGuideLayout::class,
        ];
    }
    
    public function redirect( $guide, $type){

        if($type == "section"){
            return redirect()->route('platform.guideSection.list',  $guide);
        }
    }
}
