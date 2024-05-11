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

class FilterAdPending extends Rows
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
        $pendingCampaigns = Campaign::where('active', 0);

        $sortedCategories = Categories::whereIn('id', $pendingCampaigns->pluck('category_id'))
                        ->get()
                        ->sortBy('name', SORT_NATURAL)
                        ->mapWithKeys(function(Categories $category, int $key) {
                            return [$category->id => $category->name];
                        })->toArray();
        
        $sortedRegions = Region::whereIn('id', $pendingCampaigns->pluck('region_id'))
                        ->get()
                        ->sortBy('name', SORT_NATURAL)
                        ->mapWithKeys(function(Region $region, int $key) {
                            return [$region->id => $region->name];
                        })->toArray();

        return [
            Group::make([
                Select::make('pending_campaigns_title')
                    ->title('Campaign Name')
                    ->empty('No Selection')
                    ->fromQuery($pendingCampaigns, 'title', 'title')
                    ->help('Type in boxes to search'),
                Select::make('pending_campaigns_category_id')
                    ->title('Category')
                    ->empty('No Selection')
                    ->options($sortedCategories),
                Select::make('pending_campaigns_region_id')
                    ->title('Region')
                    ->empty('No Selection')
                    ->options($sortedRegions),
            ]),
            Button::make('Filter')
                ->icon('filter')
                ->method('filterPendingCampaigns')
                ->type(Color::DEFAULT()),
        ];
    }
}
