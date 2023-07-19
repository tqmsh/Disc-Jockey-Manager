<?php

namespace App\Orchid\Screens;

use App\Models\LimoGroup;
use App\Orchid\Layouts\ViewLimoGroupLayout;
use Orchid\Screen\Screen;

class ViewLimoGroupScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'limoGroups' => LimoGroup::latest()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Limo Groups';
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
        return [
            ViewLimoGroupLayout::class
        ];
    }

    public function redirect(){
        if(request('type') == 'edit'){
            return redirect()->route('platform.limo-groups.edit', request('limo_group_id'));
        }
    }
}
