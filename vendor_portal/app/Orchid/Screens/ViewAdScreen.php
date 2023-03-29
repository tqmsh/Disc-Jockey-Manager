<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Models\VendorPackage;
use App\Orchid\Layouts\ViewAdLayoutActive;
use App\Orchid\Layouts\ViewAdLayoutInactive;
use App\Orchid\Layouts\ViewAdLayoutPending;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Toast;

class ViewAdScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            "campaignsActive"=>Campaign::where('user_id', Auth::user()->id)->where("active", 1)->paginate(10),
            "campaignsInactive"=>Campaign::where('user_id', Auth::user()->id)->where("active", 2)->paginate(10),
            "campaignsPending"=>Campaign::where('user_id', Auth::user()->id)->where("active", 0)->paginate(10),

            'metrics' => [
                'activeAds'    => ['value' => number_format(count(Campaign::where('user_id', Auth::user()->id)->where('active', 1)->get()))],
                'inactiveAds' => ['value' =>  number_format(count(Campaign::where('user_id', Auth::user()->id)->where('active', 2)->get()))],
                'total'    =>  number_format(count(Campaign::where('user_id', Auth::user()->id)->get())),
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
        return 'Your Campaigns';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create a New Campaign')
                ->icon('plus')
                ->route('platform.ad.create'),

            Button::make('Delete Selected Campaigns')
                ->icon('trash')
                ->method('deleteAds')
                ->confirm(__('Are you sure you want to delete the selected campaigns?')),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.ad.list')
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
            Layout::metrics([
                'Active Campaigns'    => 'metrics.activeAds',
                'Inactive Campaigns' => 'metrics.inactiveAds',
                'Total Campaigns' => 'metrics.total',
            ]),
            Layout::tabs([
                "Active Campaigns" => [ViewAdLayoutActive::class],
                "Inactive Campaigns" => [ViewAdLayoutInactive::class],
                "Pending Campaigns" => [ViewAdLayoutPending::class],
            ])
        ];
    }

    public function deleteAds(Request $request)
    {
        //get all campaigns from post request
        $campaigns = $request->get('campaignsSelected');

        try{
            //if the array is not empty
            if(!empty($campaigns)){

                //loop through the campaigns and delete them from db
                foreach($campaigns as $campaign){
                    Campaign::where('id', $campaign)->delete();
                }

                Toast::success('Selected campaigns deleted successfully');

            }else{
                Toast::warning('Please select campaigns in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected campaigns. Error Message: ' . $e->getMessage());
        }
    }
}
