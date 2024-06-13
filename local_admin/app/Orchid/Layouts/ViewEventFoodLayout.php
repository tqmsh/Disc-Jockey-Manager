<?php

namespace App\Orchid\Layouts;

use App\Models\Food;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Color;

class ViewEventFoodLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'food';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (Food $food){
                    return CheckBox::make('items[]')
                        ->value($food->id)
                        ->checked(false);
                }),

            TD::make()
                ->render(function (Food $food) {
                    return Button::make('Edit')
                                -> type(Color::PRIMARY())
                                -> method('redirect', ['food_id'=>$food->id, 'type'=>"edit"])
                                ->icon('pencil');
                }),

            TD::make('image', "Image")
                ->render(function (Food $food) {

                    ($food->image !=null) ? $food->image  : $food->image = "https://placehold.co/100x100?text=No+Image" ;

                    return "<img src='{$food->image}' style='width:100px;height:100px;'>";
                }),

            TD::make('name', 'Name')
                ->render(function (Food $food) {
                    return $food->name;
                }),
            
            TD::make('description', 'Description')
                ->render(function (Food $food) {
                    return $food->description;
                }),
            TD::make('nut_free', 'Nut Free')
                ->render(function (Food $food) {
                    return $food->nut_free ? 'Yes' : 'No';
                }),
            TD::make('vegetarian', 'Vegetarian')
                ->render(function (Food $food) {
                    return $food->vegetarian ? 'Yes' : 'No';
                }),
            TD::make('vegan', 'Vegan')
                ->render(function (Food $food) {
                    return $food->vegan ? 'Yes' : 'No';
                }),

            TD::make('halal', 'Halal')
                ->render(function (Food $food) {
                    return $food->halal ? 'Yes' : 'No';
                }),

            TD::make('gluten_free', 'Gluten Free')
                ->render(function (Food $food) {
                    return $food->gluten_free ? 'Yes' : 'No';
                }),
            
            TD::make('dairy_free', 'Dairy Free')
                ->render(function (Food $food) {
                    return $food->dairy_free ? 'Yes' : 'No';
                }),
            
            TD::make('kosher', 'Kosher')
                ->render(function (Food $food) {
                    return $food->kosher ? 'Yes' : 'No';
                }),
        ];
    }
}
