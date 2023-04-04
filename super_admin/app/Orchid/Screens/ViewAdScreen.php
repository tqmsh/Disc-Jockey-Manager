<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Orchid\Layouts\ViewAdLayoutInactive;
use App\Orchid\Layouts\ViewAdLayoutPending;
use App\Orchid\Layouts\ViewAdLayoutActive;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
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
            "campaignsActive"=>Campaign::where("active", 1)->paginate(10),
            "campaignsInactive"=>Campaign::where("active", 2)->paginate(10),
            "campaignsPending"=>Campaign::where("active", 0)->paginate(10),
            'metrics' => [
                'activeAds'    => ['value' => number_format(count(Campaign::where('active', 1)->get()))],
                'inactiveAds' => ['value' =>  number_format(count(Campaign::where('active', 2)->get()))],
                'total'    =>  number_format(count(Campaign::get())),
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
        return 'All Campaigns';
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
                "Pending Campaigns" => [ViewAdLayoutPending::class],
                "Active Campaigns" => [ViewAdLayoutActive::class],
                "Inactive Campaigns" => [ViewAdLayoutInactive::class],
            ])
        ];
    }

    public function updateCampaign()
    {
        $campaign = Campaign::find(request('campaign_id'));
        $campaign->active = request('active');
        $campaign->save();

        Toast::success('Campaign updated successfully!');

        return redirect()->route('platform.ad.list');
    }

    public function redirect($campaign_id){
        return redirect()->route('platform.ad.edit', $campaign_id);
    }
}
