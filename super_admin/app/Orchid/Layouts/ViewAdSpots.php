<?php

namespace App\Orchid\Layouts;

use App\Classes\DisplayAdData;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Region;
use Orchid\Support\Color;

class ViewAdSpots extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'campaignsAdSpots';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('Active Ad', 'Active Ad')
                ->render(function(array $display_ad){
                    $regionId = request('ad_spots_filters')['region_id'];

                    $active = app(DisplayAdData::class)->isDisplayAdSpotUsed($display_ad['portal'], $display_ad['route_uri'], $display_ad['ad_index'], $regionId);
                    return Button::make($active ? 'Yes' : 'No')
                            ->type($active ? Color::SUCCESS() : Color::DANGER())
                            ->method('redirectSamePage');
                }),

            TD::make('Portal', 'Portal')
                ->render(function(array $display_ad) {
                    return match((int)$display_ad['portal']) {
                        0 => 'Local Admin',
                        1 => 'Student',
                        2 => 'Vendor'
                    };
                }),
            
            TD::make('route_uri', 'Route URI')
                ->render(function(array $display_ad) {
                    return $display_ad['route_uri'];
                }),
            
            TD::make('Ad Index', 'Ad Index')
                ->render(function(array $display_ad) {
                    return $display_ad['ad_index'];
                }),
            
            TD::make('Is Square', 'Is Sqaure')
                ->render(function(array $display_ad){
                    return $display_ad['square'] == 0 ? 'No' : 'Yes';
                })
        ];
    }
}
