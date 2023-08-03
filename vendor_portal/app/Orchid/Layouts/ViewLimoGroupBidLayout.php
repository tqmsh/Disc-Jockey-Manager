<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\LimoGroup;
use Orchid\Support\Color;
use App\Models\LimoGroupMember;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class ViewLimoGroupBidLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'limo_groups';

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
                ->render(function($limoGroup){
                    return Button::make('Place Bid')->type(Color::PRIMARY())->method('redirect', ['limo_group_id' => $limoGroup->id, 'type' => 'limo_group'])->icon('plus');
                }), 

            TD::make('creator_user_id', 'Owner Email')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->owner->email);
                }),
            
            TD::make('school_id', "School")
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->school->school_name);
                })->width('175px'),

            TD::make('region_id', 'Region')
                ->render(function (LimoGroup $limoGroup) {
                    return e(Region::find($limoGroup->school->region_id)->name);
                })->width('150px'),
            
            TD::make('capacity', 'Total Capacity')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->capacity + count($limoGroup->activeMembers));
                }),
            
            TD::make('date', 'Date')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->date);
                }),

            TD::make('pickup_location', 'Pickup Location')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->pickup_location);
                }),

            TD::make('dropoff_location', 'Dropoff Location')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->dropoff_location);
                }),
                
            TD::make('depart_time', 'Depart Time')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->depart_time);
                }),

            TD::make('dropoff_time', 'Dropoff Time')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->dropoff_time);
                }),

            TD::make('notes', 'Notes')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->notes);
                })->width('225px'),
        ];
    }
}
