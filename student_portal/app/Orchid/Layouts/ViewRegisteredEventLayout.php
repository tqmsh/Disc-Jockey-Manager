<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use App\Models\EventAttendees;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;




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
            TD::make('event_name', 'Event Name')
                ->render(function (Events $event) {
                    // return e($event->event_name);

                    return Button::make($event->event_name)
                                ->type(Color::LIGHT())
                                ->method('redirect', ['event_id' => $event->id, 'type' => 'eventInformation']);
                }),
            TD::make()
                ->width('100px')
                ->align(TD::ALIGN_RIGHT)
                ->render(function($event){
                    return Button::make('Tables')
                                ->type(Color::DARK())
                                ->method('redirect', ['event_id' => $event->id, 'type' => 'table'])
                                ->icon('table');
                }), 
                
            TD::make()
                ->render(function (Events $event) {
                    return Button::make('Song Requests')
                        ->icon('music-tone-alt')         
                        ->method('redirect', ['event_id' => $event->id, 'type' => 'songs'])
                        ->type(Color::INFO());
                }),

            TD::make()
                ->render(function (Events $event) {
                    return Button::make('Election')
                        ->icon('people')         
                        ->method('redirect', ['event_id' => $event->id, 'type' => 'election'])
                        ->type(Color::LIGHT());
                }),
            TD::make()
                ->render(function($event){
                    return Button::make('Food')->method('redirect', ['event_id' => $event->id, 'type' => 'food'])->icon('pizza-slice')->type(Color::SUCCESS());
                }), 

            
            // Buy tickets button
            TD::make()
                ->render(function (Events $event) {
                    $eventAttendee = EventAttendees::where('user_id', Auth::user()->id)
                    ->where('event_id', $event->id)
                    ->first();

                    if ($eventAttendee->ticketstatus == 'Unpaid') {
                        return Link::make('Buy Tickets')
                            ->icon('money')
                            ->route('paypal', ['event_id' => $event->id])
                            ->type(Color::PRIMARY());
                    } else if ($eventAttendee->ticketstatus == 'paid'){
                        return Button::make('Tickets Bought')
                            ->icon('check')
                            ->method('redirect', ['event_id' => $event->id, 'type' => 'ticketBought'])
                            ->type(Color::SUCCESS());
                    }
                }),
            

            TD::make('event_start_time', 'Event Start Date')
                ->render(function (Events $event) {
                    return e($event->event_start_time);
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

    
        ];    
    }
}
