<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Region;
use App\Models\School;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
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
            TD::make('event_name', 'Event Name')
                ->render(function($event){
                    return e($event->event_name);
                }), 

            TD::make('school', 'School Name')
                ->render(function($event){
                    return e($event->school);
                }),    

            TD::make('region_id', 'Region')
                ->render(function($event){
                    return e(Region::where('id', School::where('id', $event->school_id)->first()->region_id)->first()->name);
                }), 

            TD::make('event_info', 'Event Info')
                ->render(function($event){
                    return e($event->event_info);
                }), 

            TD::make('event_rules', 'Event Rules')
                ->render(function($event){
                    return e($event->event_rules);
                }), 

            TD::make()
                ->width('150px')
                ->align(TD::ALIGN_RIGHT)
                ->render(function($event){
                    return Button::make('Place Bid')->type(Color::PRIMARY())->method('redirect', ['event_id' => $event->id])->icon('plus');
                }), 
          
        ];    
    }
}
