<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventInformationLayout;
use Orchid\Screen\Fields\Input;
use App\Models\Events;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Sight;





class ViewEventInformationScreen extends Screen {

    public Events $event;

    public function __construct(Events $event)
    {
        $this->event = $event;
    }

        /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->event->event_name;
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        // dd($event_id);
        return [
            'events' => Events::where('school_id', Auth::user()->student->school_id)->latest('events.created_at')->paginate(10),
            'event' => $event
        ];
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
                ->icon('arrow-left')
                ->route('platform.event.list'),
        ];
    }

    public function layout(): iterable {
    return [
        Layout::legend($this->event, [
            Sight::make('event_name', 'Event Name')->render(function (Events $event = null) {
                return $this->event->event_name;
                
            }),

            Sight::make('event_start_time', 'Event Start Date')->render(function (Events $event = null) {
                return $this->event->event_start_time;
                
            }),

            Sight::make('event_address', 'Event Address')->render(function (Events $event = null) {
                return $this->event->event_address;
                
            }),

            Sight::make('event_zip_postal', 'Event Zip/Postal')->render(function (Events $event = null) {
                return $this->event->event_zip_postal;
                
            }),

            Sight::make('event_info', 'Event Info')->render(function (Events $event = null) {
                return $this->event->event_info;
                
            }),

            Sight::make('event_rules', 'Event Rules')->render(function (Events $event = null) {
                return $this->event->event_rules;
                
            }),

            
        ]),
    ];
}

}
