<?php

namespace App\Orchid\Screens;

use App\Models\VideoTutorial;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewVendorVideoTutorialScreen extends Screen
{

    private array $video_tutorial_routes = [
        'platform.example' => 'Dashboard',
        'platform.shop' => 'Shop',
        'platform.bidopportunities.list' => 'Bid Opportunities',
        'platform.bidhistory.list' => 'Bid History',
        'platform.package.list' => 'Your Packages',
        'platform.contact-students' => 'Campaigns',
        'platform.bug-reports.list' => 'Report a Bug',
        'platform.guide.list' => 'Prom Planner Guides'
    ];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'video_tutorials' => VideoTutorial::where('portal', 2)->get()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Video Tutorials';
    }

    public function description(): ?string
    {
        return "Editing for: Vendor";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update URLs')
                ->icon('check')
                ->method('submit')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $rows = [];

        foreach($this->video_tutorial_routes as $route_name => $title) {
            $rows[] = Input::make($route_name)
                        ->title($title)
                        ->horizontal()
                        ->value(VideoTutorial::where('portal', 2)->where('route_name', $route_name)->first()->url ?? "");
        }

        return [
            Layout::rows($rows)
        ];
    }

    public function submit(Request $request) {
        try {
            // update or create video tutorial
            foreach($this->video_tutorial_routes as $route_name => $title) {
                if($request->input($route_name) !== null) {
                    VideoTutorial::updateOrCreate(
                        ['portal' => 2, 'route_name' => $route_name],
                        ['url' => $request->input($route_name)]
                    );
                }
            }

            Toast::success('You have successfully updated the video tutorial links for vendors.');

            return to_route('platform.video-tutorials.vendor');
        } catch(\Exception $e) {
            Alert::error('There was an error editing the video tutorial links. Error Code: ' . $e->getMessage());
        }
        
    }
}
