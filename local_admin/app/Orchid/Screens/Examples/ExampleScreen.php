<?php

namespace App\Orchid\Screens\Examples;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Localadmin;
use App\Models\School;
use App\Models\Vendors;
use App\Models\Student;
use App\Models\Events;
use App\Models\EventAttendees;
use App\Models\EventBids;
use App\Models\ActualExpenseRevenue;
use App\Models\DisplayAds;
use App\Orchid\Layouts\Examples\ChartBarExample;
use App\Orchid\Layouts\Examples\ChartLineExample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Questions
        // 1. Event capacity doesent exist
        // 2. Is Direct Bids received the total or unreplied
        // 3. Brands is not a thing on promplanner yet
        // 4. Is direct bids replied to only for newest event of all
        // 5. Revenue and Expenses doesn't exist
        // 6. Don't see a Checklist

        // Status meaning for event_bids:
        // 1 == accepted
        // 0 == Pending
        // 2 == Rejected

        $user = Auth::user();
        $localAdmin = LocalAdmin::where('user_id', $user->id)->first();

        // Pending Students
        $schoolId = $localAdmin->school_id;
        $numberOfStudents = Student::where('school_id', $schoolId)->count();
        $pendingStudents  = Student::where('school_id', $schoolId)->where('account_status', 0)->count();

        // Total tickets Sold
        $newestEvent = Events::where('event_creator', $user->id)->latest('created_at')->first();
        $newestEventId = !is_null($newestEvent) ? $newestEvent->id : 0;
        $paidAttendeesCount = EventAttendees::where('event_id', $newestEventId)->where('ticketstatus', 'paid')->count();

        $paidAttendeesCountPercentage = (!is_null($newestEvent) && $newestEvent->capacity > 0) ? number_format(($paidAttendeesCount / $newestEvent->capacity) * 100, 2) . " %" : "Capacity Not Set";
        
        // Direct Bids recieved
        //get the count of all bids placed on all events at the currently authenticated user's school
        $DirectBidsRecieved = EventBids::whereIn('event_id', Events::where('school_id', $schoolId)->pluck('id'))->count();

        // Direct Bids Replied to
        $DirectBidsRepliedTo = EventBids::whereIn('event_id', Events::where('school_id', $schoolId)->pluck('id'))->whereIn('status', [1,2])->count();

        // Total Revenue: 
        $revenueRecord = ActualExpenseRevenue::where('event_id', $newestEventId)->where('type', 2)->first();
        $totalRevenue = $revenueRecord ? $revenueRecord->actual : "No revenue";

        // Total Expenses:
        $expensesRecord = ActualExpenseRevenue::where('event_id', $newestEventId)->where('type', 1)->first();
        $totalExpenses = $expensesRecord ? $expensesRecord->actual : "No expenses";

        $this->campaigns = Campaign::where("region_id", School::find(Localadmin::where("user_id", Auth::user()->id)->first()->school_id)->region_id)->where("active", 1)->get();
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
                'sales'    => ['value' => number_format(6851), 'diff' => 10.08],
                'visitors' => ['value' => number_format(24668), 'diff' => -30.76],
                'totalRevenue'         => $totalRevenue,
                'totalExpenses'        => $totalExpenses,
                'totalStudents'        => $numberOfStudents,
                'totalPendingStudents' => $pendingStudents,
                'totalTicketsSold'     => $paidAttendeesCount,
                'directBidsReceived'   => $DirectBidsRecieved,
                'directBidsRepliedTo'  => $DirectBidsRepliedTo,
                'paidAttendeesCountPercentage' => $paidAttendeesCountPercentage,

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
                "company"=>Vendors::where("user_id", $campaign->user_id)->first()->company_name ?? "N/A",
                "order"=>Categories::where("id", $campaign->category_id)->first()->order_num
            ];
        }
        usort($arr_ads, [$this, 'customSort']);
        return [
            Layout::metrics([
                'Total Students' => 'metrics.totalStudents',
                'Remaining Students to onboard' => 'metrics.totalPendingStudents',
                'Total Prom Night Tickets Sold' => 'metrics.totalTicketsSold',
                'Prom Night Tickets Sold %' => 'metrics.paidAttendeesCountPercentage',
            ]),
            Layout::metrics([
                'Direct Bids received' => 'metrics.directBidsReceived',
                'Direct Bids replied' => 'metrics.directBidsRepliedTo',
                'Total Revenue' => 'metrics.totalRevenue',
                'Total Expenses' => 'metrics.totalExpenses',
            ]),
            Layout::view("card_style"),

            //Layout::columns([Layout::view("ad_marquee", ["ads"=>$arr_ads])]),
        ];
    }

}
