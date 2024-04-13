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

class ViewVideoTutorialScreen extends Screen
{

    /* portal => [
        route_name => title
       ] 
    */
    private array $video_tutorial_routes = [
        0 => [
            'platform.example' => 'Dashboard',
            'platform.event.list' => 'Events',
            'platform.event.create' => 'Add A New Event',
            'platform.suggestVendor.create' => 'Suggest a Vendor',
            'platform.student.list' => 'Students',
            'platform.student.create' => 'Add New Students',
            'platform.contact-students' => 'Contact Students',
            'platform.pendingstudent.list' => 'Pending Students',
            'platform.couples.list' => 'List Of Couples',
            'platform.limo-groups' => 'Limo Groups',
            'platform.beauty-groups' => 'Beauty Groups',
            'platform.profit.list' => 'Prom Profit',
            'platform.checklist.list' => 'Checklists',
            'platform.bug-reports.list' => 'Report a Bug',
            'platform.guide.list' => 'Prom Planner Guides'
        ],
        1 => [
            'platform.example' => 'Dashboard',
            'platform.event.list' => 'Events',
            'platform.event.tables' => 'Event Tables',
            'platform.songs.request' => 'Event Song Request',
            'platform.election.list' => 'Event Election',
            'platform.eventFood.list' => 'Event Food',
            //'' => 'Event Buy Tickets', this doesn't exist yet
            'platform.studentBids.list' => 'Bids',
            'platform.limo-groups' => 'Limo Groups',
            'platform.beauty-groups' => 'Beauty Groups',
            'platform.promdate' => 'Prom Date',
            'platform.studentSpecs.list' => 'My Specs',
            'platform.dresses' => 'Dresses',
            'platform.checklist.list' => 'Checklists',
            'platform.bug-reports.list' => 'Report a Bug',
            'platform.guide.list' => 'Prom Planner Guides'
        ],
        2 => [
            'platform.example' => 'Dashboard',
            'platform.shop' => 'Shop',
            'platform.bidopportunities.list' => 'Bid Opportunities',
            'platform.bidhistory.list' => 'Bid History',
            'platform.package.list' => 'Your Packages',
            'platform.contact-students' => 'Campaigns',
            'platform.bug-reports.list' => 'Report a Bug',
            'platform.guide.list' => 'Prom Planner Guides'
        ]
    ];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
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
        return [
            Layout::rows($this->getInputs(0))
                ->title('Local Admin URLs'),
            
            Layout::rows($this->getInputs(1))
                ->title('Student URLs'),
            
            Layout::rows($this->getInputs(2))
                ->title('Vendor URLs'),
        ];
    }

    public function submit(Request $request) {
        try {
            // Get all video tutorial routes
            for($i = 0; $i < 3; $i++) {
                foreach($this->video_tutorial_routes[$i] as $route_name => $title) {
                    $query = VideoTutorial::where('portal', $i)->where('route_name', $route_name);
                    $value = $request->input(VideoTutorial::portalToPrefix($i) . $route_name);

                    // update video tutorial
                    if($query->exists() && ($video_tutorial = $query->first())->url !== $value) {
                        // Link has been removed, delete row
                        if($value == null) {
                            $query->delete();
                        } else {
                            //update
                            $video_tutorial->url = $value;
                            $video_tutorial->save();
                        }
                    } else {
                        if($value !== null) {
                            // create new row
                            VideoTutorial::create([
                                'route_name' => $route_name,
                                'url' => $value,
                                'portal' => $i,
                            ]);

                            
                        }
                    }
                }
            }

            Toast::success('You have successfully updated the video tutorial links.');

            return to_route('platform.video-tutorials.view');
        } catch(\Exception $e) {
            Alert::error('There was an error editing the video tutorial links. Error Code: ' . $e->getMessage());
        }
        
    }

    /**
     * @return Input[]
     */
    private function getInputs(int $portal) : array {
        $rows = [];

        foreach($this->video_tutorial_routes[$portal] as $route_name => $title) {
            // to prevent overrided input names, add a prefix
            $prefix = VideoTutorial::portalToPrefix($portal);

            $rows[] = Input::make($prefix . $route_name)
                        ->title($title)
                        ->horizontal()
                        ->value(VideoTutorial::where('portal', $portal)->where('route_name', $route_name)->first()->url ?? "");
        }

        return $rows;
    }
}
