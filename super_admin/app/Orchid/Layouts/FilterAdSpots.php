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
            }

            return $str;
        };

        return [
            Group::make([
                Select::make('ad_spots_region_id')
                    ->title($title(isset(request('ad_spots_filters')['region_id'])))
                    ->empty('No Selection')
                    ->fromModel(Region::class, 'name')
                    ->help('Type in box to search'),
                CheckBox::make('ad_spots_view_open_spots')
                    ->title('View open ad spots?')
                    ->sendTrueOrFalse()
            ]),
            

            Button::make('Filter')
                ->icon('filter')
                ->method('filterAdSpots')
                ->type(Color::DEFAULT()),
        ];
    }
}
