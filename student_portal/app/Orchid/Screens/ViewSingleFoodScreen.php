<?php

namespace App\Orchid\Screens;

use App\Models\EventAttendees;
use App\Models\Events;
use App\Models\Food;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ViewSingleFoodScreen extends Screen
{
    public $event;
    public $item;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event, Food $item): iterable
    {
        abort_if(EventAttendees::where('event_id', $event->id)->where('user_id', auth()->user()->id)->where('event_id', $item->event_id)->count() == 0, 404, 'You are not registered for this item item!');
        return [
            'event' => $event,
            'item' => $item,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Item: ' . $this->item->name ;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.eventFood.list', $this->event->id),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('item', [
                Sight::make('image', 'Image')->render(function (Food $item) {
                    return '<img src="' . $item->image . '" style="max-width: 250px; max-height: 250px; margin-bottom: 3rem;</img>';
                }),
                Sight::make('name'),
                Sight::make('description'),
                Sight::make('vegetarian' , 'Vegetarian')->render(function (Food $item) {
                    return $item->vegetarian ? 'Yes' : 'No';
                }),
                Sight::make('halal' , 'Halal')->render(function (Food $item) {
                    return $item->halal ? 'Yes' : 'No';
                }),
                Sight::make('nut_free' , 'Nut Free')->render(function (Food $item) {
                    return $item->nut_free ? 'Yes' : 'No';
                }),
                Sight::make('vegan' , 'Vegan')->render(function (Food $item) {
                    return $item->vegan ? 'Yes' : 'No';
                }),
                Sight::make('gluten_free' , 'Gluten Free')->render(function (Food $item) {
                    return $item->gluten_free ? 'Yes' : 'No';
                }),
                Sight::make('dairy_free' , 'Dairy Free')->render(function (Food $item) {
                    return $item->dairy_free ? 'Yes' : 'No';
                }),
                Sight::make('kosher' , 'Kosher')->render(function (Food $item) {
                    return $item->kosher ? 'Yes' : 'No';
                }),

            ]),
        ];
    }
}
