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

class FilterAdActive extends Rows
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
        $activeCampaigns = Campaign::where('active', 1);
        return [
            Group::make([
                Select::make('active_campaigns_title')
                    ->title('Campaign Name')
                    ->empty('No Selection')
                    ->fromModel(Campaign::class, 'title', 'title')
                    ->help('Type in boxes to search'),
                Select::make('active_campaigns_category_id')
                    ->title('Category')
                    ->empty('No Selection')
                    ->fromQuery(Categories::whereIn('id', $activeCampaigns->pluck('category_id')), 'name'),
                Select::make('active_campaigns_region_id')
                    ->title('Region')
                    ->empty('No Selection')
                    ->fromQuery(Region::whereIn('id', $activeCampaigns->pluck('region_id')), 'name'),
            ]),
            Button::make('Filter')
                ->icon('filter')
                ->method('filterActiveCampaigns')
                ->type(Color::DEFAULT()),
        ];
    }
}
