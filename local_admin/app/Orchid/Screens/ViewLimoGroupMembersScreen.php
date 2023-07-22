<?php

namespace App\Orchid\Screens;

use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewLimoGroupMembersLayout;

class ViewLimoGroupMembersScreen extends Screen
{
    public $limoGroup;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(LimoGroup $limoGroup): iterable
    {
        abort_if($limoGroup->school->id != Auth::user()->localadmin->school_id, 404, 'Limo Group not found');

        return [
            'limoGroup' => $limoGroup,
            'members' => $limoGroup->members()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Members in ' . $this->limoGroup->name . ' Group';
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
                ->route('platform.limo-groups')
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
            ViewLimoGroupMembersLayout::class
        ];
    }
}
