<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventBidsLayout;
use App\Orchid\Layouts\ViewPendingEventBidsLayout;
use App\Models\Vendors;


class ViewEventBidScreen extends Screen
{
    public $event;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event,
            'pendingBids' => EventBids::filter(request(['category_id']))->where('event_id', $event->id)->where('status', 0)->latest()->paginate(min(request()->query('pagesize', 10), 100)),
            'previousBids' => EventBids::filter(request(['category_id']))->where('event_id', $event->id)->whereNot('status', 0)->orderBy('status')->latest()->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bids on: ' . $this->event->event_name;
    }

    public function description(): ?string
    {
        return 'Interested Categories: ' . ($this->event->getInterestedCategoriesNames() ?? 'None');
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Edit Interested Categories')
                ->icon('pencil')
                ->route('platform.event.edit', $this->event),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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
            Layout::rows([
                Group::make([
                    
                    Select::make('category_id')
                        ->help('Type in box to search')
                        ->empty('Filter Category')
                        ->fromModel(Categories::query(), 'name'),

                    Button::make('Filter')
                        ->icon('filter')
                        ->method('filter')
                        ->type(Color::DEFAULT()),
                ]),
                
            ]),

            Layout::tabs([
                'Pending Event Bids' => [
                    ViewPendingEventBidsLayout::class
                ],
                'Previous Event Bids' => [
                    ViewEventBidsLayout::class
                ],
            ]),
        ];
    }
    
    public function filter(Events $event)
    {
        return redirect()->route('platform.eventBids.list', [$event->id, 'category_id' => request('category_id')]);
    }

    public function redirect($bid){
        return redirect()-> route('platform.eventBids.edit', $bid);
    }

    public function updateBid(Events $event)
    {
        $bid = EventBids::find(request('bid_id'));
        $bid->status = request('choice');
        $bid->save();

        $adPrice = 50;
        $vendor = Vendors::where('user_id', $bid->user_id)->first();

        if (($bid->status) == 1) {
            $vendor->decrement('credits', $adPrice);
        } 

        Toast::success('Bid updated successfully!');
        return redirect()->route('platform.eventBids.list', $event);
    }
}
