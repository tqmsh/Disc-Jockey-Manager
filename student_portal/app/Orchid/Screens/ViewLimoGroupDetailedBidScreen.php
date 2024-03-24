<?php

namespace App\Orchid\Screens;

use App\Models\BeautyGroupBid;
use App\Models\LimoGroupBid;
use App\Models\VendorPackage;
use App\Orchid\Layouts\ViewDetailedBidLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ViewLimoGroupDetailedBidScreen extends Screen
{

    public $bid;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(LimoGroupBid $limoGroupBid): iterable
    {
        // Check for valid role and valid user
        abort_if(Auth::user()->role != 3 || Auth::user()->id != $limoGroupBid->limoGroup->creator_user_id, 403, 'You are not authorized to view this page.');

        return [
            'bid' => $limoGroupBid
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Limo Group Bid Information: ' . VendorPackage::find($this->bid->package_id)->package_name;
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
                ->route('platform.studentBids.list')
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
