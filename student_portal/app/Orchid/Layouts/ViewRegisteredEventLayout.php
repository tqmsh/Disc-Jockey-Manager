<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class ViewRegisteredEventLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'registered_events';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->width('100px')
                ->align(TD::ALIGN_RIGHT)
                ->render(function($event){
                    return Button::make('Tables')->type(Color::DARK())->method('redirect', ['event_id' => $event->id, 'type' => 'table'])->icon('table');
                }), 

            TD::make()
                ->width('100px')
                ->align(TD::ALIGN_RIGHT)
                ->render(function($event){
                    return Button::make('Unregister')->type(Color::PRIMARY())->method('redirect', ['event_id' => $event->id])->icon('close');
                }), 

            TD::make('event_name', 'Event Name')
                ->render(function (Events $event) {
                    return e($event->event_name);
                }),
            TD::make('event_start_time', 'Event Start Date')
                ->render(function (Events $event) {
                    return e($event->event_start_time);
                }),
            TD::make('school', 'School')
                ->render(function (Events $event) {
                    return e($event->school);
                }),
            TD::make('event_address', 'Event Address')
                ->render(function (Events $event) {
                    return e($event->event_address);
                }),
            TD::make('event_zip_postal', 'Event Zip/Postal')
                ->render(function (Events $event) {
                    return e($event->event_zip_postal);
                }),
            TD::make('event_info', 'Event Info')
                ->render(function (Events $event) {
                    return e($event->event_info);
                }),

            TD::make('event_rules', 'Event Rules')
                ->render(function (Events $event) {
                    return e($event->event_rules);
                }),

            TD::make('songs', 'Song')
            ->render(function (Events $event) {
                return Button::make('Song Requests')
                    ->icon('music-tone-alt')         
                    ->method('redirect', ['event_id' => $event->id, 'type' => 'songs'])
                    ->type(Color::PRIMARY());
            }),
    
        ];    
    }
}
