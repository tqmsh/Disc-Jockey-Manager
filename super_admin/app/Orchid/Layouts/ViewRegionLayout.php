<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewRegionLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'regions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make()
                ->render(function (Region $region){
                    return CheckBox::make('regions[]')
                        ->value($region->id)
                        ->checked(false);
                }),

            TD::make('name', 'Region Name')
                ->render(function (Region $region) {
                    return Link::make($region->name)
                        ->route('platform.region.edit', $region);
                }),

            TD::make('created_at', 'Created At')
                ->render(function (Region $region) {
                    return Link::make($region->created_at)
                        ->route('platform.region.edit', $region);
                }),
            TD::make('updated_at', 'Updated At')
                ->render(function (Region $region) {
                    return Link::make($region->updated_at)
                        ->route('platform.region.edit', $region);
                }),
            TD::make()
                ->render(function (Region $region) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->  method('redirect', ['region'=>$region->id]) ->icon('pencil');
                }),

                
        ];
    }
}
