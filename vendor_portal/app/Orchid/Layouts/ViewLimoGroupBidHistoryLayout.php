<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use Orchid\Support\Color;
use App\Models\LimoGroupBid;
use App\Models\VendorPackage;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class ViewLimoGroupBidHistoryLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'limoBids';

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
                ->render(function($limoBid){
                    return 
                    ($limoBid->status == 0) ? Button::make('Edit Bid')->type(Color::PRIMARY())->method('editBid', ['bidId' => $limoBid->id, 'type' => 'limo'])->icon('pencil') 
                    : '';
                }), 

            TD::make('status', 'Status')
                ->render(function($limoBid){
                    return 
                        ($limoBid->status == 0) ? '<i class="text-warning">●</i> Pending' 
                        : (($limoBid->status == 1) ? '<i class="text-success">●</i> Approved' 
                        : '<i class="text-danger">●</i> Rejected');
                }), 

            TD::make('creator_user_id', 'Owner Email')
                ->render(function (LimoGroupBid $limoBid) {
                    return e($limoBid->limoGroup->owner->email);
                }),
            
            TD::make('school_id', "School")
                ->render(function (LimoGroupBid $limoBid) {
                    return e($limoBid->limoGroup->school->school_name);
                })->width('175px'),

            TD::make('region_id', 'Region')
                ->render(function (LimoGroupBid $limoBid) {
                    return e(Region::find($limoBid->limoGroup->school->region_id)->name);
                })->width('150px'),
            
            TD::make('package_id', 'Package')
                ->render(function($studentBid){
                    return e($studentBid->package_id === null ? 'Package no longer exists': VendorPackage::find($studentBid->package_id)->package_name);
                }), 


            TD::make('notes', 'Notes')
                ->render(function($studentBid){
                    return e($studentBid->notes);
                }), 

            TD::make('contact_instructions', 'Contact Info')
                ->render(function($studentBid){
                    return e($studentBid->contact_instructions);
                }), 
            
            TD::make('created_at', 'Created At')
                ->render(function($studentBid){
                    return e($studentBid->created_at);
                }), 
        ];
    }
}
