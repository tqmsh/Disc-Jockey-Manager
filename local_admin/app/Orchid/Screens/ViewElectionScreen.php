<?php

namespace App\Orchid\Screens;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
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
        $election = Election::where('event_id', $event->id)->paginate();
        $positions = Position::where('election_id', $election->id)->paginate();
        return [
            'event' => $event,
            'election' => $election,
            'position' => $positions
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
            Layout::tabs([
                'Position 1' => [],

                'Position 2' => [],
            ]),
        ];
    }
}
