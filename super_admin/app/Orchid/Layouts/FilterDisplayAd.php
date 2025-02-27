<?php

namespace App\Orchid\Layouts;

use App\Models\DisplayAds;
use App\Models\Region;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class FilterDisplayAd extends Rows
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

        $sortedRegions = Region::whereIn('id', DisplayAds::pluck('region_id'))
                        ->get()
                        ->sortBy('name', SORT_NATURAL)
                        ->mapWithKeys(function(Region $region, int $key) {
                            return [$region->id => $region->name];
                        })->toArray();

        return [
            Group::make([
                Select::make('display_ads_route_uri')
                    ->title('Route')
                    ->empty('No Selection')
                    ->fromModel(DisplayAds::class, 'route_uri', 'route_uri')
                    ->help('Type in boxes to search'),
                Select::make('display_ads_portal')
                    ->title('Portal')
                    ->empty('No Selection')
                    ->options(array_filter([
                        0 => 'Local Admin',
                        1 => 'Student',
                        2 => 'Vendor'
                    ], fn ($value) => DisplayAds::pluck('portal')->containsStrict($value), ARRAY_FILTER_USE_KEY)),
                Select::make('display_ads_region_id')
                    ->title('Region')
                    ->empty('No Selection')
                    ->options($sortedRegions)
            ]),
            Button::make('Filter')
                ->icon('filter')
                ->method('filterDisplayAds')
                ->type(Color::DEFAULT()),
        ];
    }
}
