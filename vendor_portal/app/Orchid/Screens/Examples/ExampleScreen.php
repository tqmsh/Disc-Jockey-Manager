<?php

namespace App\Orchid\Screens\Examples;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\Vendors;
use App\Models\EventBids;
use App\Models\StudentBids;
use App\Models\LimoGroupBid;
use App\Models\Dress;
use App\Models\Wishlist;
use App\Orchid\Layouts\Examples\ChartBarExample;
use App\Orchid\Layouts\Examples\ChartLineExample;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;


class ExampleScreen extends Screen
{
    /**
     * Fish text for the table.
     */
    public const TEXT_EXAMPLE = 'Lorem ipsum at sed ad fusce faucibus primis, potenti inceptos ad taciti nisi tristique
    urna etiam, primis ut lacus habitasse malesuada ut. Lectus aptent malesuada mattis ut etiam fusce nec sed viverra,
    semper mattis viverra malesuada quam metus vulputate torquent magna, lobortis nec nostra nibh sollicitudin
    erat in luctus.';

    public $campaigns;
    function customSort($a, $b): int
    {
        // Compare order_num values
        if ($a['order'] === $b['order']) {
            return 0;
        }

        // Place 0 values at the end
        if ($a['order'] === 0) {
            return 1;
        } elseif ($b['order'] === 0) {
            return -1;
        }

        // Sort by order_num in ascending order
        return ($a['order'] < $b['order']) ? -1 : 1;
    }
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user(); // User is #197
        // dd($user->id);

        // Direct Bids Sent

        $eventBidsSent = EventBids::where('user_id', $user->id)->count();
        $studentBidsSent = StudentBids::where('user_id', $user->id)->count();
        $limoBidsSent = LimoGroupBid::where('user_id', $user->id)->count();

        $totalBidsSent = $eventBidsSent + $studentBidsSent + $limoBidsSent;

        // Direct Bids that received replies
        $eventBidsReplied = EventBids::where('user_id', $user->id)->whereIn('status', [1,2])->count();
        $studentBidsReplied = StudentBids::where('user_id', $user->id)->whereIn('status', [1,2])->count();
        $limoBidsReplied = LimoGroupBid::where('user_id', $user->id)->whereIn('status', [1,2])->count();

        $totalBidsReplied = $eventBidsReplied + $studentBidsReplied + $limoBidsReplied;

        // Wishlist Mentions
        $ownedDresses = Dress::where('user_id', $user->id)->get();

        $wishlistMentionsCount = 0;

        foreach ($ownedDresses as $dress) {
            $wishlistMentionsCount += Wishlist::where('dress_id', $dress->id)->count();
        }

        // Direct bids left
        $creditTotal = $user->credits;
        $bidsLeft = floor($creditTotal / 50);



        $this->campaigns = Campaign::where("active", 1)->get();
        return [
            'charts'  => [
                "ad_ids" =>"",
                [
                    'name'   => 'Some Data',
                    'values' => [25, 40, 30, 35, 8, 52, 17],
                    'labels' => ['12am-3am', '3am-6am', '6am-9am', '9am-12pm', '12pm-3pm', '3pm-6pm', '6pm-9pm'],
                ],
                [
                    'name'   => 'Another Set',
                    'values' => [25, 50, -10, 15, 18, 32, 27],
                    'labels' => ['12am-3am', '3am-6am', '6am-9am', '9am-12pm', '12pm-3pm', '3pm-6pm', '6pm-9pm'],
                ],
                [
                    'name'   => 'Yet Another',
                    'values' => [15, 20, -3, -15, 58, 12, -17],
                    'labels' => ['12am-3am', '3am-6am', '6am-9am', '9am-12pm', '12pm-3pm', '3pm-6pm', '6pm-9pm'],
                ],
                [
                    'name'   => 'And Last',
                    'values' => [10, 33, -8, -3, 70, 20, -34],
                    'labels' => ['12am-3am', '3am-6am', '6am-9am', '9am-12pm', '12pm-3pm', '3pm-6pm', '6pm-9pm'],
                ],
            ],
            'table'   => [
                new Repository(['id' => 100, 'name' => self::TEXT_EXAMPLE, 'price' => 10.24, 'created_at' => '01.01.2020']),
                new Repository(['id' => 200, 'name' => self::TEXT_EXAMPLE, 'price' => 65.9, 'created_at' => '01.01.2020']),
                new Repository(['id' => 300, 'name' => self::TEXT_EXAMPLE, 'price' => 754.2, 'created_at' => '01.01.2020']),
                new Repository(['id' => 400, 'name' => self::TEXT_EXAMPLE, 'price' => 0.1, 'created_at' => '01.01.2020']),
                new Repository(['id' => 500, 'name' => self::TEXT_EXAMPLE, 'price' => 0.15, 'created_at' => '01.01.2020']),

            ],
            'metrics' => [
                'totalBidsSent'     => $totalBidsSent,
                'directBidsReplied' => $totalBidsReplied,
                'bidsLeft'          => $bidsLeft,
                'wishlistMentions'  => $wishlistMentionsCount,
            ],
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Dashboard';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'View Statistics and More';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        $arr_ads = [];
        foreach ($this->campaigns as $campaign){
            if(DisplayAds::where('campaign_id', $campaign->id)->exists()) continue;
            
            $arr_ads[] = ["id"=>$campaign->id,
                "forward_url"=>$campaign->website,
                "image_url"=>$campaign->image,
                "title"=>$campaign->title,
                "category"=>Categories::where("id", $campaign->category_id)->first()->name,
                "company"=>Vendors::where("user_id", $campaign->user_id)->first()->company_name,
                "order"=>Categories::where("id", $campaign->category_id)->first()->order_num
            ];
        }
        usort($arr_ads, [$this, 'customSort']);
        return [
            Layout::metrics([
                'Direct Bids sent'    => 'metrics.totalBidsSent',
                'Direct Bids that received replies' => 'metrics.directBidsReplied',
                'Direct Bids remaining' => 'metrics.bidsLeft',
                'Wishlist mentions' => 'metrics.wishlistMentions',
            ]),

            //Layout::view("card_style"),

            //Layout::columns([Layout::view("ad_marquee", ["ads"=>$arr_ads])]),
        ];
    }

}