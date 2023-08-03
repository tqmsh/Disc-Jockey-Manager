<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\BeautyGroup;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class ViewBeautyGroupBidLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'beauty_groups';

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
                ->render(function($beautyGroup){
                    return Button::make('Place Bid')->type(Color::PRIMARY())->method('redirect', ['beauty_group_id' => $beautyGroup->id, 'type' => 'beauty_group'])->icon('plus');
                }), 

            TD::make('creator_user_id', 'Owner Email')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->owner->email);
                }),
            
            TD::make('school_id', "School")
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->school->school_name);
                })->width('175px'),

            TD::make('region_id', 'Region')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e(Region::find($beautyGroup->school->region_id)->name);
                })->width('150px'),
            
            TD::make('capacity', 'Total Capacity')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->capacity + count($beautyGroup->activeMembers));
                }),
            
            TD::make('date', 'Date')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->date);
                }),

            TD::make('pickup_location', 'Pickup Location')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->pickup_location);
                }),

            TD::make('dropoff_location', 'Dropoff Location')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->dropoff_location);
                }),
                
            TD::make('depart_time', 'Depart Time')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->depart_time);
                }),

            TD::make('dropoff_time', 'Dropoff Time')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->dropoff_time);
                }),

            TD::make('notes', 'Notes')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->notes);
                })->width('225px'),
        ];
    }
}
