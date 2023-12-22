<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use App\Models\User;
use App\Models\Vendors;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;


use App\Models\EventBids;
use App\Models\StudentBids;
use App\Models\LimoGroupBid;

class ViewVendorCreditsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'vendors';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('company_name', 'Company Name')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->company_name)
                        ->route('platform.vendor.payments', $vendor);
                }),
            
            TD::make('credits', 'Remaining Credits')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->credits)
                        ->route('platform.vendor.payments', $vendor);
                }),
            
            TD::make('used_credits', 'Used Credits')
                ->render(function (Vendors $vendor) {
                    $eventBidsSent = EventBids::where('user_id', $vendor->user_id)->count();
                    $studentBidsSent = StudentBids::where('user_id', $vendor->user_id)->count();
                    $limoBidsSent = LimoGroupBid::where('user_id', $vendor->user_id)->count();

                    $totalBidsSent = $eventBidsSent + $studentBidsSent + $limoBidsSent;
                    $creditsUsed = $totalBidsSent * 50;

                    return Link::make($creditsUsed)
                        ->route('platform.vendor.payments', $vendor);
                }),
            
            TD::make()
                ->render(function (Vendors $vendor) {
                    return Button::make('Payment History')-> type(Color::PRIMARY())->method('redirect', ['vendor'=>$vendor->id])->icon('dollar');
                }),
        ];
    }

}
