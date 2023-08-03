<?php

namespace App\Orchid\Screens;

use App\Models\BeautyGroup;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewBeautyGroupMembersLayout;

class ViewBeautyGroupMembersScreen extends Screen
{
    public $beautyGroup;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(BeautyGroup $beautyGroup): iterable
    {
        abort_if($beautyGroup->school->id != Auth::user()->localadmin->school_id, 404, 'Beauty Group not found');

        return [
            'beautyGroup' => $beautyGroup,
            'members' => $beautyGroup->members()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Members in ' . $this->beautyGroup->name . ' Group';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->route('platform.beauty-groups')
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
            ViewBeautyGroupMembersLayout::class
        ];
    }
}
