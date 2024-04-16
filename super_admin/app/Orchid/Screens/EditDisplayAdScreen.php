<?php

namespace App\Orchid\Screens;

use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditDisplayAdScreen extends Screen
{

    public $display_ad;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(DisplayAds $display_ad): iterable
    {
        return [
            'display_ad' => $display_ad
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editing Display Ad: ' . $this->display_ad->campaign->name;
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

            Button::make('Update')
                ->icon('check')
                ->method('updateDisplayAd')
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
                    ->placeholder('/events/campaigns/{display_ad}')
                    ->value($this->display_ad->route_uri),
            
                Input::make('ad_index')
                    ->title('Ad Index')
                    ->mask([
                        'numericInput' => true
                    ])
                    ->required()
                    ->horizontal()
                    ->placeholder('What ad index?')
                    ->value($this->display_ad->ad_index),

                Select::make('portal')
                    ->title('Portal')
                    ->required()
                    ->horizontal()
                    ->options([
                        0 => 'Local Admin',
                        1 => 'Student',
                        2 => 'Vendor'
                    ])
                    ->value($this->display_ad->portal),

                Input::make('campaign_name')
                    ->title('Campaign Name')
                    ->placeholder('Enter your campaign name')
                    ->required()
                    ->horizontal()
                    ->value($this->display_ad->campaign->title),

                Input::make('campaign_link')
                    ->title('Campaign URL')
                    ->type("url")
                    ->placeholder('https://placeholder.com')
                    ->required()
                    ->horizontal()
                    ->value($this->display_ad->campaign->website),

                Select::make('campaign_region')
                    ->title("Region")
                    ->required()
                    ->fromModel(Region::class, 'name')
                    ->horizontal()
                    ->value($this->display_ad->campaign->region_id),

                Select::make('category_id')
                    ->title('Category')
                    ->required()
                    ->fromModel(Categories::class, 'name')
                    ->horizontal()
                    ->value($this->display_ad->campaign->category_id),

                Input::make('campaign_image')
                    ->title('Campaign Image')
                    ->placeholder('https://example.com/image.png')
                    ->horizontal()
                    ->required()
                    ->value($this->display_ad->campaign->image),

                CheckBox::make('square')
                    ->title('Is Square?')
                    ->horizontal()
                    ->value($this->display_ad->square),
            ])
        ];
    }

    public function updateDisplayAd(DisplayAds $display_ad, Request $request) {
        try {
            $display_ad->update([
                'route_uri' => $request->input('route_uri'),
                'ad_index' => $request->input('ad_index'),
                'portal' => $request->input('portal'),
                'square' => intval($request->boolean('square')),
                'region_id' => $request->input("campaign_region")
            ]);

            $display_ad->campaign->update([
                "category_id" => $request->input("category_id"),
                "region_id" => $request->input("campaign_region"),
                "title" => $request->input("campaign_name"),
                "image" => $request->input("campaign_image"),
                "website" => $request->input("campaign_link"),
            ]);

            Toast::success('Successfully updated the display ad.');

            return to_route('platform.ad.list');
        } catch(\Exception $e) {
            Toast::error("There was an error trying to edit this display ad. Error message: {$e->getMessage()}");
        }
    }
}
