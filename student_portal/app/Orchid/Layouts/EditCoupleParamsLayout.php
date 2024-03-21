<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class EditCoupleParamsLayout extends Rows
{

    protected function fields(): iterable
    {
        return [
            Input::make('couple_name')
                ->title('Couple Name')
                ->placeholder('Enter your couple name')
                ->required()
                ->help('Enter the name of your couple.')
                ->horizontal()
                ->value($this->query->get('couple_name')),
            Input::make('status')
                ->title('Status')
                ->placeholder('Enter your couple status')
                ->required()
                ->help('Enter the status of your couple.')
                ->horizontal()
                ->value($this->query->get('status')),
            Input::make('description')
                ->title('Description')
                ->placeholder('Enter your couple description')
                ->required()
                ->help('Enter the description of your couple.')
                ->horizontal()
                ->value($this->query->get('description')),
            Button::make('Update')
                ->icon('check')
                ->method('update'),
            Button::make('Break Up')
                ->icon('')
                ->method('breakup')
                ->confirm(__('Are you sure you want to break up?'))
                ->parameters([
                    'couple_id' => $this->query->get("couple_id"),
                ])
        ];
    }
}
