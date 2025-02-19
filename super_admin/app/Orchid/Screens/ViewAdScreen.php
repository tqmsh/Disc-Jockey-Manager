<?php

namespace App\Orchid\Screens;

use App\Classes\DisplayAdData;
use Exception;
use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\LoginAds;
use App\Models\Region;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewAdLayoutActive;
use App\Orchid\Layouts\ViewAdLayoutPending;
use App\Orchid\Layouts\ViewAdLayoutInactive;
use App\Models\Vendors;
use App\Orchid\Layouts\CreateLoginAd;
use App\Orchid\Layouts\FilterAdActive;
use App\Orchid\Layouts\FilterAdInactive;
use App\Orchid\Layouts\FilterAdPending;
use App\Orchid\Layouts\FilterAdSpots;
use App\Orchid\Layouts\FilterDisplayAd;
use App\Orchid\Layouts\FilterLoginAds;
use App\Orchid\Layouts\ViewDisplayAd;
use App\Orchid\Layouts\ViewAdSpots;
use App\Orchid\Layouts\ViewLoginAds;

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
          "campaignsActive"=>Campaign::where("active", 1)->filter(request('active_campaigns_filters') ?? [])->paginate(min(request()->query('pagesize', 10), 100)),
          "campaignsInactive"=>Campaign::where("active", 2)->filter(request('inactive_campaigns_filters') ?? [])->paginate(min(request()->query('pagesize', 10), 100)),
          "campaignsPending"=>Campaign::where("active", 0)->filter(request('pending_campaigns_filters') ?? [])->paginate(min(request()->query('pagesize', 10), 100)),
          "campaignsDisplayAds" =>  DisplayAds::filter(request('display_ads_filters') ?? [])->paginate(min(request()->query('pagesize', 10), 100)),
          "campaignsAdSpots" => request('ad_spots_filters') == null ? [] : app(DisplayAdData::class)->getAllAdSpots(request('ad_spots_filters')),
          "campaignsLoginAds" => LoginAds::filter(request('login_ads_filters') ?? [])->paginate(min(request()->query('pagesize', 10), 100)),
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
            Link::make('Create Display Ad')
                ->icon('plus')
                ->route('platform.ad.create.display-ad'),

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
              "Pending Campaigns" => [FilterAdPending::class, ViewAdLayoutPending::class],
              "Active Campaigns" => [FilterAdActive::class, ViewAdLayoutActive::class],
              "Inactive Campaigns" => [FilterAdInactive::class, ViewAdLayoutInactive::class],
              "Display Ads" => [FilterDisplayAd::class, ViewDisplayAd::class],
              "Ad Spots" => [FilterAdSpots::class, ViewAdSpots::class],
              "Login/Register Ads" => [FilterLoginAds::class, ViewLoginAds::class, CreateLoginAd::class]
            ])->activeTab(request('active_tab') ?? 'Pending Campaigns')
          
        ];
    }

    public function deleteAds(Request $request)
    {
        //get all campaigns from post request
        $campaigns = $request->get('campaignsSelected');

        try{
            //if the array is not empty
            if(!empty($campaigns)){
                
                Campaign::whereIn('id', $campaigns)->delete();

                Toast::success('Selected campaigns deleted successfully');

            }else{
                Toast::warning('Please select campaigns in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected campaigns. Error Message: ' . $e->getMessage());
        }
    }

    public function updateCampaign()
    {
        $campaign = Campaign::find(request('campaign_id'));

        $adPrice = 50;
        $vendor = Vendors::where('user_id', $campaign->user_id)->first();

        $campaign->active = request('active');
        $campaign->save();

        if (($campaign->active) == 1) {
            $vendor->decrement('credits', $adPrice);
        } 

        Toast::success('Campaign updated successfully!');

        return redirect()->route('platform.ad.list');
    }

    public function createLoginAd(Request $request) {
        try {
            $data = $request->validate([
                'title' => 'required',
                'subtitle' => 'required',
                'button_title' => 'required',
                'website' => 'required',
                'image' => 'required',
                'portal' => 'required'
            ]);

            $campaign = Campaign::create([
                "user_id" => 197, //!NEED TO OPTIMIZE THIS LATER
                "category_id" => Categories::all()->first()->id,
                "region_id" => Region::all()->first()->id,
                "title" => $data['title'],
                "image" => $data['image'],
                "website" => $data['website'],
                "clicks" => 0,
                "impressions" => 0,
                'active' => 1
            ]);

            // Make LoginAds model with website and image removed from array.
            LoginAds::create(
                array_merge(
                    array_diff_key($data, array_flip(['website', 'image'])),
                    ['campaign_id' => $campaign->id]
                )
            );

            Toast::success('Login ad created successfully!');

            return to_route('platform.ad.list', ['active_tab' => 'Login/Register Ads']);
        } catch(\Exception $e) {
            Alert::error('There was a error trying to create a login ad. Error Message: ' . $e->getMessage());
        }
    }

    public function redirect($campaign_id){
        return redirect()->route('platform.ad.edit', $campaign_id);
    }

    public function redirectDisplayAd($display_ad_id) {
        return to_route('platform.ad.edit.display-ad', $display_ad_id);
    }
  
    public function redirectSamePage() {
        return to_route('platform.ad.list');
    }

    public function redirectLoginAd($login_ad_id) {
        return to_route('platform.ad.login-ad.edit', $login_ad_id);
    }
 
    public function filterPendingCampaigns()
    {
        return redirect()->route('platform.ad.list', [
            'pending_campaigns_filters' => [
                'title' => request('pending_campaigns_title'),
                'category_id' => request('pending_campaigns_category_id'),
                'region_id' => request('pending_campaigns_region_id'),
            ], 
            'active_tab' => 'Pending Campaigns',
        ]);
    }

    public function filterActiveCampaigns()
    {
        return redirect()->route('platform.ad.list', [
            'active_campaigns_filters' => [
                'title' => request('active_campaigns_title'),
                'category_id' => request('active_campaigns_category_id'),
                'region_id' => request('active_campaigns_region_id'),
            ], 
            'active_tab' => 'Active Campaigns',
        ]);
    }

    public function filterInactiveCampaigns()
    {
        return redirect()->route('platform.ad.list', [
            'inactive_campaigns_filters' => [
                'title' => request('inactive_campaigns_title'),
                'category_id' => request('inactive_campaigns_category_id'),
                'region_id' => request('inactive_campaigns_region_id'),
            ], 
            'active_tab' => 'Inactive Campaigns',
        ]);
    }

    public function filterDisplayAds()
    {
        return redirect()->route('platform.ad.list', [
            'display_ads_filters' => [
                'route_uri' => request('display_ads_route_uri'),
                'portal' => request('display_ads_portal'),
                'region_id' => request('display_ads_region_id'),
            ], 
            'active_tab' => 'Display Ads',
        ]);
    }

    public function filterAdSpots() {
        return redirect()->route('platform.ad.list', [
            'ad_spots_filters' => [
                'region_id' => request('ad_spots_region_id'),
                'view_open_spots' => request('ad_spots_view_open_spots')
            ],
            'active_tab' => 'Ad Spots'
        ]);
    }

    public function filterLoginAds() {
        return redirect()->route('platform.ad.list', [
            'login_ads_filters' => [
                'portal' => request('login_ads_portal')
            ],
            'active_tab' => 'Login/Register Ads'
        ]);
    }
}
