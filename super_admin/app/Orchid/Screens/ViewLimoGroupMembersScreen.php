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
        return [
            'limoGroup' => $limoGroup,
            'members' => $limoGroup->members()->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Members in: ' . $this->limoGroup->name . ' Group';
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
