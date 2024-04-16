<?php

namespace App\Orchid\Screens\Examples;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\School;
use App\Models\Student;
use App\Models\Vendors;
use App\Models\EventAttendees;
use App\Models\Events;
use App\Models\StudentBids;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

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
        $user = Auth::user();
        $now = Carbon::now();

        // Remaining Days Until Prom
        $closestAttendingEvent = Events::whereIn('id',
            EventAttendees::where('user_id', $user->id)->pluck('event_id')
        )->where('event_start_time', '>', $now)->oldest('event_start_time')->first();
        // Check if an event has been found
        if($closestAttendingEvent !== null) {
            $eventStartTime = $closestAttendingEvent->event_start_time;
            $eventStartTime = Carbon::parse($eventStartTime);
            $daysLeftUntilEvent = $now->diffInDays($eventStartTime, false);
        }

        if (!isset($daysLeftUntilEvent) || $daysLeftUntilEvent < 0) {
            $daysLeftUntilEvent = "No Upcoming Events";
        }

        // Add no upencoming event

        // Bid Counting
        $TotalDirectBidsReceived = StudentBids::where('student_user_id', $user->id)->count();
        $TotalBidsRepliedTo = StudentBids::where('student_user_id', $user->id)->whereIn('status', [1,2])->count();



        $this->campaigns = Campaign::where("region_id", School::find(Student::where("user_id", Auth::user()->id)->first()->school_id)->region_id)->where("active", 1)->get();
        return [
            "ad_ids" =>"",
            'charts'  => [
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
                'daysLeftUntilEvent'           => $daysLeftUntilEvent,
                'ItemsRemainingOnChecklist'    => 0,
                'totalDirectBidsReceived'   => $TotalDirectBidsReceived,
                'totalDirectBidsRepliedTo' => $TotalBidsRepliedTo,
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
                'Remaining Days Until Prom' => 'metrics.daysLeftUntilEvent',
                'Items remaining on Checklist' => 'metrics.ItemsRemainingOnChecklist',
                'Total Direct Bids Received' => 'metrics.totalDirectBidsReceived',
                'Total Direct Bids Replied To' => 'metrics.totalDirectBidsRepliedTo',
            ]),
            Layout::view("card_style"),

            Layout::columns([Layout::view("ad_marquee", ["ads"=>$arr_ads])]),
        ];
    }
}
