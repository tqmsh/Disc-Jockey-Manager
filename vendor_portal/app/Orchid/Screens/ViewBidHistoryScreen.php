<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\EventBidLayout;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\Auth;

class ViewBidHistoryScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'eventBids' => Auth::user()->eventBids->sortBy('status'),
            'studentBids' => Auth::user()->studentBids
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bid History';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            EventBidLayout::class,
        ];
    }

    public function editBid($bidId){
        return redirect()->route('platform.bid.edit', $bidId);
    }
}
