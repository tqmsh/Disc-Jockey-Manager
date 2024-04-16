<?php

namespace App\Orchid\Screens;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateDisplayAdScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        dd(Route::current()->uri());
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Display Ad';
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
                ->route('platform.ad.list'),

            Button::make('Create Display Ad')
                ->icon('plus')
                ->method('createAd')
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
                Input::make('route_uri')
                    ->title('Route URI')
                    ->required()
                    ->horizontal()
                    ->placeholder('e.g., admin/events/campaigns/{display_ad}'),
            
                Input::make('ad_index')
                    ->title('Ad Index')
                    ->type('number')
                    ->mask([
                        'numericInput' => true
                    ])
                    ->required()
                    ->horizontal()
                    ->placeholder('What ad index?'),

                Select::make('portal')
                    ->title('Portal')
                    ->empty('What portal should this be in?')
                    ->required()
                    ->horizontal()
                    ->options([
                        0 => 'Local Admin',
                        1 => 'Student',
                        2 => 'Vendor'
                    ]),

                Input::make('campaign_name')
                    ->title('Campaign Name')
                    ->placeholder('Enter your campaign name')
                    ->required()
                    ->horizontal(),

                Input::make('campaign_link')
                    ->title('Campaign URL')
                    ->type("url")
                    ->placeholder('https://placeholder.com')
                    ->required()
                    ->horizontal(),

                Select::make('campaign_region')
                    ->title("Region")
                    ->empty('Start typing to search...')
                    ->required()
                    ->fromModel(Region::class, 'name')
                    ->horizontal(),

                Select::make('category_id')
                    ->title('Category')
                    ->empty('Choose a category...')
                    ->required()
                    ->fromModel(Categories::class, 'name')
                    ->horizontal(),

                Input::make('campaign_image')
                    ->title('Campaign Image')
                    ->placeholder('https://example.com/image.png')
                    ->horizontal()
                    ->required(),

                CheckBox::make('square')
                    ->title('Is Square?')
                    ->horizontal(),
            ])
        ];
    }

    public function createAd(Request $request) {
        try {
            // Check for existing route.
            if($this->validAd($request)) {
                // Create the campaign
                $campaign = Campaign::create([
                    "user_id" => Auth::user()->id,
                    "category_id" => $request->input("category_id"),
                    "region_id" => $request->input("campaign_region"),
                    "title" => $request->input("campaign_name"),
                    "image" => $request->input("campaign_image"),
                    "website" => $request->input("campaign_link"),
                    "clicks" => 0,
                    "impressions" => 0,
                    'active' => 1
                ]);

                // Create the display ad
                DisplayAds::create([
                    'route_uri' => $request->input('route_uri'),
                    'ad_index' => $request->input('ad_index'),
                    'portal' => $request->input('portal'),
                    'campaign_id' => $campaign->id,
                    'region_id' => $request->input("campaign_region"),
                    'square' => intval($request->boolean('square'))
                ]);

                Toast::success("Successfully created an ad.");

                return to_route('platform.ad.list');
            } else {
                Toast::error("There's already an ad at that ad index in that route.");
            }
        } catch(\Exception $e) {
            Toast::error("There's been an error trying to create an ad. Error message: {$e->getMessage()}");
        }
    }

    private function validAd(Request $request) : bool {
        return !(DisplayAds::where('portal', $request->input('portal'))
                            ->where('route_uri', $request->input('route_uri'))
                            ->where('ad_index', $request->input('ad_index'))
                            ->where('region_id', $request->input("campaign_region"))
                            ->exists()
                );
    }
}
