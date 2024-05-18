<?php

namespace App\Orchid\Screens\Examples;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\School;
use App\Models\Student;
use App\Models\Vendors;
use App\Models\Session;
use App\Orchid\Layouts\Examples\ChartPieExample;
use Carbon\Carbon;
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
use DateTime;
use DateInterval;
// i123333

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
        $this->campaigns = Campaign::where("active", 1)->get();
        $prov_arr = School::select(['state_province', 'count(*)'])->groupBy('province');
        $prov_keys = array();
        $prov_counts = array();
        foreach ($prov_arr as $p=>$val) {
            $vals = explode(",", $val);
            $secondvals = explode(":", $vals[0]);
            $thirdvals = explode(":", $vals[1]);
            $thirdvals[1] = str_replace("}", "", $thirdvals[1]);
            array_push($prov_keys, $secondvals[1]);
            array_push($prov_counts, $thirdvals[1]);
        }
        dd($prov_keys);
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
                'totalSchools'    => School::count(),
                'totalStudents'   => Student::count(),
                'totalVendors'    => Vendors::count(),
                'totalBrands'     => Vendors::count(),

                'schoolSessionAvgDuration' => Session::averageDurationForSchools(),
                'studentsSessionAvgDuration' => Session::averageDurationForStudents(),
                'vendorsSessionAvgDuration' => Session::averageDurationForVendors(),
                'brandsSessionAvgDuration' => Session::averageDurationForBrands(),





            ],
                'RegionChart'  => [
                    [
                        'name'   => 'Agreements Broken Down By Province',
                        'values' => $prov_counts,
                        'labels' => $prov_keys
                    ]
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

        $now = new DateTime(); // Create a DateTime object representing the current date and time

        // Calculate the date and time 30 days ago
        $oneDayAgo = clone $now;
        $oneDayAgo->sub(new DateInterval('P1D'));

        $sevenDaysAgo = clone $now;
        $sevenDaysAgo->sub(new DateInterval('P7D'));

        $thirtyDaysAgo = clone $now;
        $thirtyDaysAgo->sub(new DateInterval('P30D'));

        $ninetyDaysAgo = clone $now;
        $ninetyDaysAgo->sub(new DateInterval('P90D'));

        // Count the number of schools created in the last 30 days
        $numberOfSchoolsLastDay    = School::where('created_at', '>=', $oneDayAgo->format('Y-m-d H:i:s'))->count();
        $numberOfSchoolsLast7Days  = School::where('created_at', '>=', $sevenDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfSchoolsLast30Days = School::where('created_at', '>=', $thirtyDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfSchoolsLast90Days = School::where('created_at', '>=', $ninetyDaysAgo->format('Y-m-d H:i:s'))->count();

        $numberOfStudentsLastDay    = Student::where('created_at', '>=', $oneDayAgo->format('Y-m-d H:i:s'))->count();
        $numberOfStudentsLast7Days  = Student::where('created_at', '>=', $sevenDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfStudentsLast30Days = Student::where('created_at', '>=', $thirtyDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfStudentsLast90Days = Student::where('created_at', '>=', $ninetyDaysAgo->format('Y-m-d H:i:s'))->count();

        $numberOfVendorsLastDay    = Vendors::where('created_at', '>=', $oneDayAgo->format('Y-m-d H:i:s'))->count();
        $numberOfVendorsLast7Days  = Vendors::where('created_at', '>=', $sevenDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfVendorsLast30Days = Vendors::where('created_at', '>=', $thirtyDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfVendorsLast90Days = Vendors::where('created_at', '>=', $ninetyDaysAgo->format('Y-m-d H:i:s'))->count();

        $numberOfBrandsLastDay    = Vendors::where('created_at', '>=', $oneDayAgo->format('Y-m-d H:i:s'))->count();
        $numberOfBrandsLast7Days  = Vendors::where('created_at', '>=', $sevenDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfBrandsLast30Days = Vendors::where('created_at', '>=', $thirtyDaysAgo->format('Y-m-d H:i:s'))->count();
        $numberOfBrandsLast90Days = Vendors::where('created_at', '>=', $ninetyDaysAgo->format('Y-m-d H:i:s'))->count();


        // dd($numberOfStudentsLast90Days);

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
                'Total Schools'  => 'metrics.totalSchools',
                'Total Students' => 'metrics.totalStudents',
                'Total Vendors'  => 'metrics.totalVendors',
                'Total Brands'   => 'metrics.totalBrands',
            ]),

            Layout::view("new_schools",[ //Schools
                                        'numOfSchools1'  => $numberOfSchoolsLastDay,
                                        'numOfSchools7'  => $numberOfSchoolsLast7Days,
                                        'numOfSchools30' => $numberOfSchoolsLast30Days,
                                        'numOfSchools90' => $numberOfSchoolsLast90Days,
                                        //Students
                                        'numOfStudents1'  => $numberOfStudentsLastDay,
                                        'numOfStudents7'  => $numberOfStudentsLast7Days,
                                        'numOfStudents30' => $numberOfStudentsLast30Days,
                                        'numOfStudents90' => $numberOfStudentsLast90Days,

                                        //Vendors
                                        'numOfVendors1'  => $numberOfVendorsLastDay,
                                        'numOfVendors7'  => $numberOfVendorsLast7Days,
                                        'numOfVendors30' => $numberOfVendorsLast30Days,
                                        'numOfVendors90' => $numberOfVendorsLast90Days,

                                        //Brands
                                        'numOfBrands1'  => $numberOfBrandsLastDay,
                                        'numOfBrands7'  => $numberOfBrandsLast7Days,
                                        'numOfBrands30' => $numberOfBrandsLast30Days,
                                        'numOfBrands90' => $numberOfBrandsLast90Days,
                                        ]),

            Layout::metrics([
                'Schools Avg Session duration'   => 'metrics.schoolSessionAvgDuration',
                'Students Avg Session duration'  => 'metrics.studentsSessionAvgDuration',
                'Vendors Avg Session duration'  => 'metrics.vendorsSessionAvgDuration',
                'Brands Avg Session duration'  => 'metrics.brandsSessionAvgDuration',



            ]),

            ChartPieExample::make('RegionChart', 'Pie Chart')
                ->description('Simple, responsive, modern SVG Charts with zero dependencies'),


            // Layout::metrics([
            //     'Sales Today'    => 'metrics.sales',
            //     'Visitors Today' => 'metrics.visitors',
            //     'Pending Orders' => 'metrics.orders',
            //     'Total Earnings' => 'metrics.total',
            // ]),
            // Layout::metrics([
            //     'Sales Today'    => 'metrics.sales',
            //     'Visitors Today' => 'metrics.visitors',
            //     'Pending Orders' => 'metrics.orders',
            //     'Total Earnings' => 'metrics.total',
            // ]),

            //Layout::view("card_style"),

            //Layout::columns([Layout::view("ad_marquee", ["ads"=>$arr_ads])]),

        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        return response()->streamDownload(function () {
            $csv = tap(fopen('php://output', 'wb'), function ($csv) {
                fputcsv($csv, ['header:col1', 'header:col2', 'header:col3']);
            });

            collect([
                ['row1:col1', 'row1:col2', 'row1:col3'],
                ['row2:col1', 'row2:col2', 'row2:col3'],
                ['row3:col1', 'row3:col2', 'row3:col3'],
            ])->each(function (array $row) use ($csv) {
                fputcsv($csv, $row);
            });

            return tap($csv, function ($csv) {
                fclose($csv);
            });
        }, 'File-name.csv');
    }
}



            // Layout::columns([
            //     ChartLineExample::make('charts', 'Line Chart')
            //         ->description('It is simple Line Charts with different colors.'),

            //     ChartBarExample::make('charts', 'Bar Chart')
            //         ->description('It is simple Bar Charts with different colors.'),
            // ]),

            // Layout::table('table', [
            //     TD::make('id', 'ID')
            //         ->width('150')
            //         ->render(function (Repository $model) {
            //             // Please use view('path')
            //             return "<img src='https://loremflickr.com/500/300?random={$model->get('id')}'
            //                   alt='sample'
            //                   class='mw-100 d-block img-fluid rounded-1 w-100'>
            //                 <span class='small text-muted mt-1 mb-0'># {$model->get('id')}</span>";
            //         }),

            //     TD::make('name', 'Name')
            //         ->width('450')
            //         ->render(fn (Repository $model) => Str::limit($model->get('name'), 200)),

            //     TD::make('price', 'Price')
            //         ->render(fn (Repository $model) => '$ ' . number_format($model->get('price'), 2)),

            //     TD::make('created_at', 'Created'),
            // ]),
