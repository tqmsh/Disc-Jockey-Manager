<?php

namespace App\Orchid\Layouts;

use App\Models\Dress;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class DressListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    protected $target = 'dresses';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('select', ' ')
                ->align(TD::ALIGN_CENTER)
                ->render(function (Dress $dress) {
                    return CheckBox::make('selected[]')
                        ->value($dress->id)
                        ->placeholder(' ')
                        ->checked(false);
                }),

            TD::make('model_number', 'Model Number')
                ->sort()
                ->filter(),

            TD::make('model_name', 'Model Name')
                ->sort()
                ->filter(),

            TD::make('url', 'URL')
                ->sort()
                ->filter(),

            TD::make('created_at', 'Created At')
                ->sort()
                ->render(function ($dress) {
                    return $dress->created_at->toDateTimeString();
                }),

            TD::make('updated_at', 'Last Updated')
                ->sort()
                ->render(function ($dress) {
                    return $dress->updated_at->toDateTimeString();
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Dress $dress) {
                    return Link::make('Details')
                        ->route('platform.dresses.edit', $dress->id)
                        ->type(Color::INFO())
                        ->icon('eye');
                }),
        ];
    }
}
