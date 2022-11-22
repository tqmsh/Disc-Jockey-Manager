<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;

class ViewEventLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'events';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (Events $event){
                    return CheckBox::make('events[]')
                        ->value($event->id)
                        ->checked(false);
                }),
            TD::make('id', 'Id')
                ->render(function (Events $event) {
                    return Link::make($event->id)
                        ->route('platform.event.edit', $event);
                }),
            TD::make('event_name', 'Event Name')
                ->render(function (Events $event) {
                    return Link::make($event->event_name)
                        ->route('platform.event.edit', $event);
                }),
            TD::make('event_start_time', 'Event Start Date')
                ->render(function (Events $event) {
                    return Link::make($event->event_start_time)
                        ->route('platform.event.edit', $event);
                }),
            TD::make('school', 'School')
                ->render(function (Events $event) {
                    return Link::make($event->school)
                        ->route('platform.event.edit', $event);
                }),
            TD::make('event_address', 'Event Address')
                ->render(function (Events $event) {
                    return Link::make($event->event_address)
                        ->route('platform.event.edit', $event);
                }),
            TD::make('event_zip_postal', 'Event Zip/Postal')
                ->render(function (Events $event) {
                    return Link::make($event->event_zip_postal)
                        ->route('platform.event.edit', $event);
                }),
            TD::make('event_info', 'Event Info')
                ->render(function (Events $event) {
                    return Link::make($event->event_info)
                        ->route('platform.event.edit', $event);
                }),

            TD::make('event_rules', 'Event Rules')
                ->render(function (Events $event) {
                    return Link::make($event->event_rules)
                        ->route('platform.event.edit', $event);
                }),
        ];    
    }
}
