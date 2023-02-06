<?php

namespace App\Orchid\Screens;

use App\Models\EventBids;
use App\Models\StudentBids;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\EventBidLayout;
use App\Orchid\Layouts\StudentBidLayout;

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
            'eventBids' => EventBids::where('user_id', Auth::user()->id)->orderBy('status')->paginate(10),
            'studentBids' => StudentBids::where('user_id', Auth::user()->id)->orderBy('status')->paginate(10),
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
            StudentBidLayout::class
        ];
    }

    public function editBid($bidId, $type){
        if($type == 'event'){
            return redirect()->route('platform.eventBid.edit', $bidId);
        } else {
            return redirect()->route('platform.studentBid.edit', $bidId);
        }
    }
}
