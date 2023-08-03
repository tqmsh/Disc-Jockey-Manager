<?php

namespace App\Orchid\Layouts;

use App\Models\Dress;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewDressListLayout extends Table
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
            TD::make('image', 'Image')
                ->render(function (Dress $dress) {
                    $images = $dress->images;
                    return empty($images) ? 'No Image' : "<img src='{$images[0]}' width='100px' onerror='this.onerror=null;this.parentNode.innerHTML=\"No Image\";'/>";
                }),

            TD::make('company_name', 'Brand')
                ->sort()
                ->filter()
                ->render(function (Dress $dress) {
                    return $dress->vendor->company_name;
                }),

            TD::make('model_number', 'Model Number')
                ->sort()
                ->filter()
                ->render(function (Dress $dress) {
                    return "<div style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>{$dress->model_number}</div>";
                }),

            TD::make('model_name', 'Model Name')
                ->sort()
                ->filter()
                ->render(function (Dress $dress) {
                    return "<div style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>{$dress->model_name}</div>";
                }),

            TD::make('url', 'URL')
                ->sort()
                ->filter()
                ->render(function (Dress $dress) {
                    return "<div style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'><a href='" . e($dress->url) . "' target='_blank' title='" . e($dress->url) . "'>" . e($dress->url) . "</a></div>";
                }),

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
                        ->type(Color::INFO())
                        ->icon('eye')
                        ->route('platform.dresses.detail', ["dress" => $dress->getKey(), "back" => "platform.dresses"]);
                }),
        ];
    }
}
