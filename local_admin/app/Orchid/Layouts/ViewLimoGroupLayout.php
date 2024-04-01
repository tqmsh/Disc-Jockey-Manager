<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\LimoGroup;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewLimoGroupLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'limoGroups';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make()
                ->render(function (LimoGroup $limoGroup) {
                    return Button::make('Members')-> type(Color::PRIMARY())->method('redirect', ['limo_group_id'=> $limoGroup->id, 'type' => 'members'])->icon('people-group');
                }),

            TD::make('creator_user_id', 'Owner Email')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->owner->email);
                })->defaultHidden(),

            TD::make('name', 'Name')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->name);
                }),
            
            TD::make('school_id', "School")
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->school->school_name);
                })->width('175px'),

            TD::make('region_id', 'Region')
                ->render(function (LimoGroup $limoGroup) {
                    return e(Region::find($limoGroup->school->region_id)->name);
                })->width('150px')->defaultHidden(),
            
            TD::make('capacity', 'Capacity')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->capacity + count($limoGroup->activeMembers));
                })->defaultHidden(),
            
            TD::make('date', 'Date')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->date);
                }),

            TD::make('pickup_location', 'Pickup Location')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->pickup_location);
                })->defaultHidden(),

            TD::make('dropoff_location', 'Dropoff Location')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->dropoff_location);
                })->defaultHidden(),
                
            TD::make('depart_time', 'Depart Time')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->depart_time);
                })->defaultHidden(),

            TD::make('dropoff_time', 'Dropoff Time')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->dropoff_time);
                })->defaultHidden(),

            TD::make('notes', 'Notes')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->notes);
                })->width('225px')->defaultHidden(),

            TD::make('created_at', 'Created At')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->created_at);
                })->defaultHidden(),

            TD::make('updated_at', 'Updated At')
                ->render(function (LimoGroup $limoGroup) {
                    return e($limoGroup->updated_at);
                })->defaultHidden(),
        ];
    }
}
