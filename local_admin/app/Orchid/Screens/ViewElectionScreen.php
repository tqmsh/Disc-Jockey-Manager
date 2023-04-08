<?php

namespace App\Orchid\Screens;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Orchid\Layouts\ViewPositionLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class ViewElectionScreen extends Screen
{
    public $event;
    public $elections;
    public $position;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $election = Election::where('event_id', $event->id);
        $position = Position::where('election_id', $election->pluck('id'));
        return [
            'event' => $event,
            'election' => $election,
            'position' => $position
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Prom Vote: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create Election')
                ->icon('plus')
                ->route('platform.event.list'),

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
        return [
            ViewPositionLayout::class
        ];
    }
}
