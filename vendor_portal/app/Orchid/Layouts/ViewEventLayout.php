<?php

namespace App\Orchid\Layouts;

use App\Models\Categories;
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

            TD::make()
                ->align(TD::ALIGN_RIGHT)
                ->render(function($event){
                    return Button::make('Place Bid')->type(Color::PRIMARY())->method('redirect', ['event_id' => $event->id, 'type' => 'event'])->icon('plus');
                }), 

            TD::make('school', 'School Name')
                ->render(function($event){
                    return e($event->school);
                }),    

            TD::make('event_start_time', 'Event Start')
                ->render(function($event){
                    return e($event->event_start_time);
                }),
            
            TD::make('interested_vendor_categories', 'Interested Categories')
                ->render(function($event){
                    $interestedCategories = $event->getInterestedCategoriesNames();
                    return e($event->getInterestedCategoriesNames());
                })->defaultHidden(),
        ];    
    }
}
