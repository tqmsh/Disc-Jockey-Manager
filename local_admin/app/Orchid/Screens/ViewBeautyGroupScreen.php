<?php

namespace App\Orchid\Screens;

use App\Models\BeautyGroup;
use App\Models\Localadmin;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewBeautyGroupLayout;

class ViewBeautyGroupScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'beautyGroups' => BeautyGroup::where('school_id', Localadmin::where("user_id",Auth::user()->id)->first()->school_id)->latest()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Beauty Groups';
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
            ViewBeautyGroupLayout::class
        ];
    }

    public function redirect(){
        if(request('type') == 'members'){
            return redirect()->route('platform.beauty-groups.members', request('beauty_group_id'));
        }
    }
}
