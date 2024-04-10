<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Orchid\Layouts\ViewChecklistLayout;
use Orchid\Screen\Screen;

class ViewChecklistScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        // get all student checklists
        $checklists = Checklist::where('type', 0)->latest()->get();

        return [
            'checklists' => $checklists
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Checklists';
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
            ViewChecklistLayout::class
        ];
    }

    public function redirect($checklist_id) {
        return to_route('platform.checklist-items.list', $checklist_id);
    }
}
