<?php

namespace App\Orchid\Screens;

use App\Models\EventBids;
use App\Models\Events;
use App\Models\Localadmin;
use App\Models\VendorPackage;
use App\Orchid\Layouts\ViewDetailedBidLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ViewEventDetailedBidScreen extends Screen
{

    public $event;
    public $bid;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event, EventBids $eventBid): iterable
    {
        // Check for valid role and valid user
        abort_if(Auth::user()->role != 2 || Localadmin::where('user_id', Auth::user()->id)->value('school_id') != $event->school_id, 403, 'You are not authorized to view this page.');

        return [
            'event' => $event,
            'bid' => $eventBid
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Event Bid Information: ' . VendorPackage::find($this->bid->package_id)->package_name;
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
                ->route('platform.eventBids.list', $this->event->id)
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
            ViewDetailedBidLayout::class  
        ];
    }
}
