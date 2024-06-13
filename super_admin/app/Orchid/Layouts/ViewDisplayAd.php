<?php

namespace App\Orchid\Layouts;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\DisplayAds;
use App\Models\Region;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewDisplayAd extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'campaignsDisplayAds';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (DisplayAds $display_ad){
                    return CheckBox::make('campaignsSelected[]')
                        ->value($display_ad->campaign->id)
                        ->checked(false);
                }),
            TD::make()
                ->width('80')
                ->align(TD::ALIGN_LEFT)
                ->render(function(DisplayAds $display_ad){
                    return Button::make('Edit')->type(Color::PRIMARY())->method('redirectDisplayAd', ['display_ad_id' => $display_ad->id])->icon('pencil');
                }),

            TD::make('route_name', 'Route')
                ->render(function(DisplayAds $display_ad){
                    return e($display_ad->route_uri);
                }),

            TD::make('portal', 'Portal')
                ->render(function(DisplayAds $display_ad){
                    return e($display_ad->portalToName());
                }),

            TD::make('ad_index', 'Ad Index')
                ->render(function(DisplayAds $display_ad){
                    return e($display_ad->ad_index);
                }),

            TD::make('gender', 'Gender')
                ->render(function(DisplayAds $display_ad){
                    return e(ucfirst($display_ad->gender));
                })->defaultHidden(),
            
            TD::make('square', 'Square')
                ->render(function(DisplayAds $display_ad){
                    return e(boolval($display_ad->square) ? "Yes" : "No");
                }),

            TD::make('campaign_name', 'Campaign Name')
                ->render(function(DisplayAds $display_ad){
                    return e($display_ad->campaign->title);
                })->defaultHidden(),

            TD::make('category', 'Category')
                ->render(function(DisplayAds $display_ad){
                    return e(Categories::find($display_ad->campaign->category_id)->name);
                })->defaultHidden(),

            TD::make('region_id', 'Region')
                ->render(function(DisplayAds $display_ad){
                    return e(Region::find($display_ad->campaign->region_id)->name);
                }),

            TD::make('campaign_image', 'Campaign Image')
                ->render(function(DisplayAds $display_ad){
                    return "<img src='{$display_ad->campaign->image}' width='200'>";
                }),

            TD::make('campaign_url', 'Campaign URL')
                ->render(function(DisplayAds $display_ad){
                    return Link::make($display_ad->campaign->website)
                        ->href($display_ad->campaign->website == null ? '#' : $display_ad->campaign->website);
                }),
            TD::make('clicks', 'Clicks')
                ->render(function(DisplayAds $display_ad){
                    return e($display_ad->campaign->clicks);
                }),
            TD::make('impressions', 'Impressions')
                ->render(function(DisplayAds $display_ad){
                    return e($display_ad->campaign->impressions);
                })->defaultHidden(),
        ];
    }
}
