<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Region;
use App\Models\VendorPaidRegions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditAdScreen extends Screen
{
    public $campaign;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Campaign $campaign): iterable
    {
        return [
            'campaign' => $campaign
        ];

    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Campaign';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Package')
                ->icon('trash')
                ->method('delete'),

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
            Layout::rows([
                Input::make('campaign_name')
                    ->title('Campaign Name')
                    ->placeholder('Enter your campaign name')
                    ->required()
                    ->help('Enter the name of your package.')
                    ->horizontal()
                    ->value($this->campaign->title),
                Select::make('campaign_category')
                    ->title("Category")
                    ->empty('Start typing to search...')
                    ->required()
                    ->help('Enter the category for your campaign.')
                    ->fromQuery(Categories::query(), 'name')
                    ->horizontal()
                    ->value($this->campaign->category_id),
                Cropper::make("campaign_image")
                    ->storage("public")
                    ->title("Image")
                    ->width(600)
                    ->height(600)
                    ->required()
                    ->help("Image to display")
                    ->horizontal()
                    ->value($this->campaign->image),
                Input::make('campaign_link')
                    ->title('Campaign URL')
                    ->type("url")
                    ->placeholder('https://placeholder.com')
                    ->required()
                    ->help('Enter the link to forward to.')
                    ->horizontal()
                    ->value($this->campaign->website),
                Select::make('campaign_region')
                    ->title("Region")
                    ->empty('Start typing to search...')
                    ->required()
                    ->help('Enter the region for your campaign.')
                    ->fromModel(VendorPaidRegions::class, "region_id") // TODO Display names, return ids
                    ->horizontal()
                    ->value($this->campaign->region_id),
            ]),
        ];
    }


    public function update(Campaign $campaign, Request $request){

        try{

            if($this->validAd($request)){

                $campaign = $campaign->fill(
                    ["user_id"=>Auth::user()->id,
                    "category_id"=>$request->input("campaign_category"),
                    "region_id"=>$request->input("campaign_region"),
                    "title"=>$request->input("campaign_name"),
                    "image"=>$request->input("campaign_image"),
                    "website"=>$request->input("campaign_link")
                    ])->save();

                if($campaign){
                    Toast::success('Campaign updated successfully!');
                    return redirect()->route('platform.ad.list');
                }else{
                    Alert::error('Error: Campaign not updated for an unknown reason.');
                }
            }else{
                Toast::error('Campaign name already exists.');
            }

        } catch (Exception $e) {
            Alert::error('Error: ' . $e->getMessage());
        }
    }

    public function delete(Campaign $campaign)
    {
        try{

            $campaign->delete();

            Toast::info('You have successfully deleted the campaign.');

            return redirect()->route('platform.ad.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this campaign. Error Code: ' . $e);
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
