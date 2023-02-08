<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventBidsLayout;
use App\Orchid\Layouts\ViewPendingEventBidsLayout;

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
            'pendingBids' => EventBids::where('event_id', $event->id)->where('status', 0)->paginate(10),
            'previousBids' => EventBids::where('event_id', $event->id)->whereNot('status', 0)->orderBy('status')->paginate(10)
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
            Layout::tabs([
                'Pending Bids' => [
                    ViewPendingEventBidsLayout::class
                ],
                'Previous Bids' => [
                    ViewEventBidsLayout::class
                ],
            ]),
        ];
    }

    public function updateBid(Events $event)
    {
        $bid = EventBids::find(request('bid_id'));
        $bid->status = request('choice');
        $bid->save();
        return redirect()->route('platform.eventBids.list', $event);
    }
}
