<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Region;
use App\Models\School;
use App\Models\VendorPackage;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class EventBidLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'eventBids';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make()
                ->align(TD::ALIGN_LEFT)
                ->render(function($eventBid){
                    return 
                    ($eventBid->status == 0) ? Button::make('Edit Bid')->type(Color::PRIMARY())->method('editBid', ['bidId' => $eventBid->id])->icon('pencil') 
                    : '';
                }), 

            TD::make('status', 'Status')
                ->render(function($eventBid){
                    return 
                        ($eventBid->status == 0) ? '<i class="text-warning">●</i> Pending' 
                        : (($eventBid->status == 1) ? '<i class="text-success">●</i> Approved' 
                        : '<i class="text-danger">●</i> Rejected');
                }),

            TD::make('event_id', 'Event')
                ->render(function($eventBid){
                    return e($eventBid->event_id === null ? 'Event no longer exists': Events::find($eventBid->event_id)->event_name);
                }),

            TD::make('school_name', 'School')->width('200px')
                ->render(function($eventBid){
                    return e($eventBid->school_name === null ? 'School no longer exists' : $eventBid->school_name);
                }),

            TD::make('region_id', 'Region')
                ->render(function($eventBid){
                    return e(Region::find($eventBid->region_id)->name);
                }), 

            TD::make('package_id', 'Package')
                ->render(function($eventBid){
                    return e($eventBid->package_id === null ? 'Package no longer exists': VendorPackage::find($eventBid->package_id)->package_name);
                }), 


            TD::make('notes', 'Notes')
                ->render(function($eventBid){
                    return e($eventBid->notes);
                }), 

            TD::make('created_at', 'Created At')
                ->render(function($eventBid){
                    return e($eventBid->created_at);
                }), 

        ];
    }
}
