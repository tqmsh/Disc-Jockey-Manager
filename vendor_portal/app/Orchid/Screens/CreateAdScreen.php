<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Region;
use App\Models\Vendors;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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

            Button::make('Create Campaign')
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

                    $catName = Categories::find($this->vendor->category_id)->name;

                    return $catName;
                }),])->title('Information'),

            Layout::rows([

                Input::make('campaign_name')
                    ->title('Campaign Name')
                    ->placeholder('Enter your campaign name')
                    ->required()
                    ->help('Enter the name of your package.')
                    ->horizontal(),
                Cropper::make("campaign_image")
                    ->storage("s3")
                    ->title("Image")
                    ->width(600)
                    ->height(600)
                    ->required()
                    ->help("Image to display")
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
                ]),
        ];
    }

    public function createAd(Request $request){
        try{

            if($this->validAd($request)){
                Campaign::create([
                    "user_id"=>Auth::user()->id,
                    "category_id"=>Vendors::where('user_id', Auth::user()->id)->first()->category_id,
                    "region_id"=>$request->input("campaign_region"),
                    "title"=>$request->input("campaign_name"),
                    "image"=>$request->input("campaign_image"),
                    "website"=>$request->input("campaign_link"),
                    "clicks"=>0,
                    "impressions"=>0
                ]);
                //toast success message
                Toast::success('Campaign Created Successfully');
                //redirect to vendor list
                return redirect()->route('platform.ad.list');

            }else{
                Toast::error('Campaign already exists in this region.');
            }

        }catch(Exception $e){

            //toast error message
            Alert::error('There was an error creating this campaign Error Code: ' . $e->getMessage());
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
