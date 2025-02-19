<?php

namespace App\Orchid\Layouts;

use App\Models\Campaign;
use App\Models\Categories;
use App\Models\Region;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class FilterAdInactive extends Rows
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
        $inactiveCampaigns = Campaign::where('active', 2);

        $sortedCategories = Categories::whereIn('id', $inactiveCampaigns->pluck('category_id'))
                        ->get()
                        ->sortBy('name', SORT_NATURAL)
                        ->mapWithKeys(function(Categories $category, int $key) {
                            return [$category->id => $category->name];
                        })->toArray();
        
        $sortedRegions = Region::whereIn('id', $inactiveCampaigns->pluck('region_id'))
                        ->get()
                        ->sortBy('name', SORT_NATURAL)
                        ->mapWithKeys(function(Region $region, int $key) {
                            return [$region->id => $region->name];
                        })->toArray();

        return [
            Group::make([
                Select::make('inactive_campaigns_title')
                    ->title('Campaign Name')
                    ->empty('No Selection')
                    ->fromQuery($inactiveCampaigns, 'title', 'title')
                    ->help('Type in boxes to search'),
                Select::make('inactive_campaigns_category_id')
                    ->title('Category')
                    ->empty('No Selection')
                    ->options($sortedCategories),
                Select::make('inactive_campaigns_region_id')
                    ->title('Region')
                    ->empty('No Selection')
                    ->options($sortedRegions),
            ]),
            Button::make('Filter')
                ->icon('filter')
                ->method('filterInactiveCampaigns')
                ->type(Color::DEFAULT()),
        ];
    }
}
