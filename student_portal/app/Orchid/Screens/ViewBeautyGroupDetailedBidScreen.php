<?php

namespace App\Orchid\Screens;

use App\Models\BeautyGroupBid;
use App\Models\VendorPackage;
use App\Orchid\Layouts\ViewDetailedBidLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ViewBeautyGroupDetailedBidScreen extends Screen
{

    public $bid;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(BeautyGroupBid $beautyGroupBid): iterable
    {
        // Check for valid role and valid user
        abort_if(Auth::user()->role != 3 || Auth::user()->id != $beautyGroupBid->beautyGroup->creator_user_id, 403, 'You are not authorized to view this page.');

        return [
            'bid' => $beautyGroupBid
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Beauty Group Bid Information: ' . VendorPackage::find($this->bid->package_id)->package_name;
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
