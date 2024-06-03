<?php

namespace App\Orchid\Layouts;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Region;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewAdLayoutActive extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'campaignsActive';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (Campaign $campaign){
                    return CheckBox::make('campaignsSelected[]')
                        ->value($campaign->id)
                        ->checked(false);
                }),
            TD::make()
                ->align(TD::ALIGN_LEFT)
                ->width('100px')
                ->render(function(Campaign $campaign){
                    return Button::make('Deactivate')->method('updateCampaign', ['campaign_id' => $campaign->id, 'active' => 2])->icon('close')->type(Color::DANGER());
                }),
            TD::make()
                ->width('80')
                ->align(TD::ALIGN_LEFT)
                ->render(function(Campaign $campaign){
                    return Button::make('Edit')->type(Color::PRIMARY())->method('redirect', ['campaign_id' => $campaign->id])->icon('pencil');
                }),
            TD::make('event_name', 'Campaign Name')
                ->render(function(Campaign $campaign){
                    return e($campaign->title);
                }),

            TD::make('category', 'Category')
                ->render(function(Campaign $campaign){
                    return e(Categories::find($campaign->category_id)->name);
                }),

            TD::make('region_id', 'Region')
                ->render(function(Campaign $campaign){
                    return e(Region::find($campaign->region_id)->name);
                }),

            TD::make('campaign_image', 'Campaign Image')
                ->render(function(Campaign $campaign){
                    return "<img src='$campaign->image' width='200'>";
                }),

            TD::make('campaign_url', 'Campaign URL')
                ->render(function(Campaign $campaign){
                    return Link::make($campaign->website)
                        ->href($campaign->website == null ? '#' : $campaign->website);
                }),
            TD::make('clicks', 'Clicks')
                ->render(function(Campaign $campaign){
                    return e($campaign->clicks);
                }),
            TD::make('impressions', 'Impressions')
                ->render(function(Campaign $campaign){
                    return e($campaign->impressions);
                }),
        ];
    }
}
