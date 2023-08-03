<?php

namespace App\Orchid\Layouts;

use App\Models\Vendors;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;

class DressEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('dress.model_number')
                ->title('Model Number')
                ->placeholder('Model number'),

            Input::make('dress.brand')
                ->title('Brand')
                ->value(Vendors::firstWhere("user_id", auth()->id())->company_name)
                ->readonly(),

            Input::make('dress.model_name')
                ->title('Model Name')
                ->placeholder('Model name'),

            TextArea::make('dress.description')
                ->title('Description')
                ->rows(5)
                ->placeholder('Description'),

            TextArea::make('dress.colours')
                ->title('Colours')
                ->rows(5)
                ->help('Input colours as newline-separated values.')
                ->placeholder('Colours'),

            TextArea::make('dress.sizes')
                ->title('Sizes')
                ->rows(5)
                ->help('Input sizes as newline-separated values.')
                ->placeholder('Sizes'),

            TextArea::make('dress.images')
                ->title('Images')
                ->rows(5)
                ->help('Input image URLs as newline-separated values.')
                ->placeholder('Images'),

            Input::make('dress.url')
                ->title('URL')
                ->placeholder('URL'),
        ];
    }
}
