<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Region;
use App\Models\Vendors;
use App\Notifications\GeneralNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateAdScreen extends Screen
{
    public $paidRegionIds = [];
    public $vendor;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $this->vendor = Vendors::where('user_id', Auth::user()->id)->first();
        $array = Auth::user()->paidRegions->toArray();

        //get all the region_ids of the array
        $this->paidRegionIds =  Arr::pluck($array, ['region_id']);
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Campaign';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Create Campaign (50 credits)')
                ->icon('plus')
                ->method('createAd'),

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
            Layout::legend("category",[
                Sight::make('category_id', 'Your Category')->render(function(){

                    return Categories::find($this->vendor->category_id)->name;
                }),])->title('Information'),

            Layout::rows([

                Input::make('campaign_name')
                    ->title('Campaign Name')
                    ->placeholder('Enter your campaign name')
                    ->required()
                    ->help('Enter the name of your package.')
                    ->horizontal(),
                Input::make('campaign_link')
                    ->title('Campaign URL')
                    ->type("url")
                    ->placeholder('https://placeholder.com')
                    ->required()
                    ->help('Enter the link to forward to.')
                    ->horizontal(),
                Select::make('campaign_region')
                    ->title("Region")
                    ->empty('Start typing to search...')
                    ->required()
                    ->help('Enter the region for your campaign.')
                    ->fromQuery(Region::query()->whereIn('id', $this->paidRegionIds), 'name')
                    ->horizontal(),
                Cropper::make("campaign_image")
                     ->storage("s3")
                    ->title("Image")
                    ->width(env("AD_SIZE"))
                    ->height(env("AD_SIZE"))
                    ->required()
                    ->help("Image to display")
                    ->acceptedFiles('.png, .jpg, .jpeg,')
                    ->horizontal()
                ]),
        ];
    }

public function createAd(Request $request)
{
    try {
        // Check credits
        if (Auth::user()->vendor->credits >= 50) {
            // Check if the ad is valid
            if ($this->validAd($request)) {
                // Create the campaign
                Campaign::create([
                    "user_id" => Auth::user()->id,
                    "category_id" => Vendors::where('user_id', Auth::user()->id)->first()->category_id,
                    "region_id" => $request->input("campaign_region"),
                    "title" => $request->input("campaign_name"),
                    "image" => $request->input("campaign_image"),
                    "website" => $request->input("campaign_link"),
                    "clicks" => 0,
                    "impressions" => 0
                ]);

                // Send notification to all super admins that a vendor has created an ad
                $super_admins = User::where('role', 1)->get();
                Notification::send($super_admins, new GeneralNotification([
                    'title' => 'New Campaign Created',
                    'message' => Auth::user()->firstname . ' ' . Auth::user()->lastname . ' has created a new campaign. Please accept or reject it.',
                    'action' => '/admin/campaigns',
                ]));

                // Toast success message
                Toast::success('Campaign Created Successfully');
                // Redirect to the ad list
                return redirect()->route('platform.ad.list');
            } else {
                // Toast error message
                Toast::error('Campaign already exists in this region.');
            }
        } else {
            // Toast error message for insufficient credits
            Toast::error('Insufficient Credits');
            // Redirect to the shop
            return redirect()->route('platform.shop');
        }
    } catch (Exception $e) {
        // Toast error message
        Alert::error('There was an error creating this campaign. Error Code: ' . $e->getMessage());
    }
}

    public function validAd(Request $request){
        return count(Campaign::where("user_id", Auth::user()->id)
            ->where("category_id", $request->input("campaign_category"))
            ->where("title", $request->input("campaign_name"))
            ->where("region_id", $request->input("campaign_region"))
            ->where("website", $request->input("campaign_link"))->get()
        ) == 0;
    }
}
