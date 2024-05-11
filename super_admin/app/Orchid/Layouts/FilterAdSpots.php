<?php

namespace App\Orchid\Layouts;

use App\Models\DisplayAds;
use App\Models\Region;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class FilterAdSpots extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $title = function(bool $rgIdSet) : string{
            $str = 'Region';

            if($rgIdSet) {
                $region = Region::find(request('ad_spots_filters')['region_id'])->name;
                $str .= " (Currently seeing ad spots for: {$region})";
            } else {
                $str .= " (Required)";
            }

            return $str;
        };

        $sortedRegions = Region::whereIn('id', DisplayAds::pluck('region_id'))
                        ->get()
                        ->sortBy('name', SORT_NATURAL)
                        ->mapWithKeys(function(Region $region, int $key) {
                            return [$region->id => $region->name];
                        })->toArray();

        return [
            Group::make([
                Select::make('ad_spots_region_id')
                    ->title($title(isset(request('ad_spots_filters')['region_id'])))
                    ->empty('No Selection')
                    ->help('Type in box to search')
                    ->options($sortedRegions),
                Select::make('ad_spots_view_open_spots')
                    ->title("Viewing Options (Optional)")
                    ->empty('No Selection')
                    ->options([
                        0 => "Open",
                        1 => "Used"
                    ])
            ]),
            

            Button::make('Filter')
                ->icon('filter')
                ->method('filterAdSpots')
                ->type(Color::DEFAULT()),
        ];
    }
}
