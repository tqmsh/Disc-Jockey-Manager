<?php

namespace App\Orchid\Layouts;

use App\Models\TourElement;
use App\Models\TourScreen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewTourElementLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'tourElements';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (TourElement $tourElement){
                    return CheckBox::make('tourElement[]')
                        ->value($tourElement->id)
                        ->checked(false);
                }),

            TD::make()
                ->render(function (TourElement $tourElement) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->method('redirect', ['tour_element_id'=> $tourElement->id, 'type' => 'edit'])->icon('pencil');
                }),

            TD::make('screen', 'Screen')
                ->render(function (TourElement $tourElement) {
                    return e($tourElement->screenOwner->screen);
                }),

            TD::make('title', 'Title')
                ->render(function (TourElement $tourElement) {
                    return e($tourElement->title);
                }),

            TD::make('description', 'Description')
                ->render(function (TourElement $tourElement) {
                    return e($tourElement->description);
                }),

            TD::make('order_element', 'Order of the elements')
                ->render(function (TourElement $tourElement) {
                    return e($tourElement->order_element);
                }),



        ];
    }
}
