<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\BeautyGroup;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewBeautyGroupLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'beautyGroups';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make('checkboxes')
                ->render(function (BeautyGroup $beautyGroup){
                    return CheckBox::make('beautyGroups[]')
                        ->value($beautyGroup->id)
                        ->checked(false);
                }),

            TD::make()
                ->render(function (BeautyGroup $beautyGroup) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->method('redirect', ['beauty_group_id'=> $beautyGroup->id, 'type' => 'edit'])->icon('pencil');
                }),

            TD::make()
                ->render(function (BeautyGroup $beautyGroup) {
                    return Button::make('Members')-> type(Color::DARK())->method('redirect', ['beauty_group_id'=> $beautyGroup->id, 'type' => 'members'])->icon('people-group');
                }),

            TD::make('creator_user_id', 'Owner Email')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->owner->email);
                })->defaultHidden(),

            TD::make('name', 'Name')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->name);
                }),
            
            TD::make('school_id', "School")
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->school->school_name);
                })->width('175px'),

            TD::make('region_id', 'Region')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e(Region::find($beautyGroup->school->region_id)->name);
                })->width('150px')->defaultHidden(),
            
            TD::make('capacity', 'Capacity')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->capacity);
                })->defaultHidden(),
            
            TD::make('date', 'Date')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->date);
                }),

            TD::make('pickup_location', 'Pickup Location')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->pickup_location);
                })->defaultHidden(),

            TD::make('dropoff_location', 'Dropoff Location')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->dropoff_location);
                })->defaultHidden(),
                
            TD::make('depart_time', 'Depart Time')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->depart_time);
                })->defaultHidden(),

            TD::make('dropoff_time', 'Dropoff Time')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->dropoff_time);
                })->defaultHidden(),

            TD::make('notes', 'Notes')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->notes);
                })->width('225px')->defaultHidden(),

            TD::make('created_at', 'Created At')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->created_at);
                })->defaultHidden(),

            TD::make('updated_at', 'Updated At')
                ->render(function (BeautyGroup $beautyGroup) {
                    return e($beautyGroup->updated_at);
                })->defaultHidden(),
        ];
    }
}
