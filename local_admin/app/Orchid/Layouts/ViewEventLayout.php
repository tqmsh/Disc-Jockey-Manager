<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

use App\Models\EventsHistoricalRecord;

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
            TD::make('checkboxes')
                ->render(function (Events $event){
                    return CheckBox::make('events[]')
                        ->value($event->id)
                        ->checked(false);
                }),
            TD::make('event_name', 'Event Name')
                ->render(function (Events $event) { 
                    return Link::make($event->event_name)
                        ->route('platform.event.edit', $event);
                }),
            // TD::make()
            //     ->render(function($event){
            //         return Button::make('Students')->method('redirect', ['event_id' => $event->id, 'type' => 'student'])->icon('people')->type(Color::DARK());
            //     }), 

            // TD::make()
            //     ->render(function($event){
            //         return Button::make('Bids')->method('redirect', ['event_id' => $event->id, 'type' => 'event'])->icon('dollar')->type(Color::PRIMARY());
            //     }), 

            // TD::make()
            //     ->render(function($event){
            //         return Button::make('Promvote')->method('redirect', ['event_id' => $event->id, 'type' => 'promvote'])->icon('people')->type(Color::LIGHT());
            //     }), 

            // TD::make()
            //     ->render(function (Events $event) {
            //         return Button::make('Song Requests')
            //             ->icon('music-tone-alt')         
            //             ->method('redirect', ['event_id' => $event->id, 'type' => 'songReq'])
            //             ->type(Color::INFO());
            //     }),

            // TD::make()
            //     ->render(function($event){
            //         return Button::make('Food')->method('redirect', ['event_id' => $event->id, 'type' => 'food'])->icon('pizza-slice')->type(Color::SUCCESS());
            //     }), 
            
            // TD::make()
            //     ->render(function($event){
            //         $existingRecord = EventsHistoricalRecord::where('event_id', $event->id)->first();
            //         if($existingRecord){
            //             return Button::make('Edit Historical Record')->method('redirect', ['event_id' => $event->id, 'type' => 'editHistory'])->icon('pencil')->type(Color::WARNING());
            //         } else {
            //             return Button::make('Add Historical Record')->method('redirect', ['event_id' => $event->id, 'type' => 'createHistory'])->icon('plus')->type(Color::ERROR());
            //         }
            //     }), 
                


            // TD::make()
            //     ->render(function($event){
            //         return Button::make('Profit')->method('redirect', ['event_id' => $event->id, 'type' => 'profit'])->icon('money')->type(Color::SECONDARY());
            //     }), 

            // TD::make()
            //     ->render(function (Events $event) {
            //         return Button::make('Edit')-> type(Color::PRIMARY())-> method('redirect', ['event_id'=>$event->id, 'type'=>"edit"])->icon('pencil');
            //     }),

        ];    
    }
}