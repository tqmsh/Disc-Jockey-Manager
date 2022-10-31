<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;

class ViewEventScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'events' => Events::latest('events.created_at')->filter(request(['country', 'state_province', 'school', 'school_board']))->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Events';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Event')
                ->icon('plus')
                ->route('platform.event.create'),

            Button::make('Delete Selected Events')
                ->icon('trash')
                ->method('deleteEvents')
                ->confirm(__('Are you sure you want to delete the selected events?')),

                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
