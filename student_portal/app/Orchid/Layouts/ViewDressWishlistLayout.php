<?php

namespace App\Orchid\Layouts;

use App\Models\Dress;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewDressWishlistLayout extends ViewDressListLayout
{
    /**
     * Data source.
     *
     * @var string
     */
    protected $target = 'wishlistDresses';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        $columns = array_merge(
            [
                TD::make('select', ' ')
                    ->align(TD::ALIGN_CENTER)
                    ->render(function (Dress $dress) {
                        return CheckBox::make('selected[]')
                            ->value($dress->id)
                            ->placeholder(' ')
                            ->checked(false);
                    }),
            ],
            parent::columns()
        );
        // We want the "back" parameter to be different here compared to the ViewDressListLayout
        $actionColumn = TD::make('actions', 'Actions')
            ->align(TD::ALIGN_CENTER)
            ->width('100px')
            ->render(function (Dress $dress) {
                return Link::make('Details')
                    ->type(Color::INFO())
                    ->icon('eye')
                    ->route('platform.dresses.detail', ["dress" => $dress->getKey(), "back" => "platform.dresses.wishlist"]);
            });

        // replace last column, which is 'Actions' in the parent class
        array_pop($columns);
        $columns[] = $actionColumn;

        return $columns;
    }
}
