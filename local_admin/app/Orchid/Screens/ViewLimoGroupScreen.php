<?php

namespace App\Orchid\Screens;

use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewLimoGroupLayout;

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
            'limoGroups' => LimoGroup::where('school_id', Auth::user()->localadmin->school_id)->latest()->paginate(10)
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
        if(request('type') == 'members'){
            return redirect()->route('platform.limo-groups.members', request('limo_group_id'));
        }
    }
}
