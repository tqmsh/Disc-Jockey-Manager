<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class FilterLoginAds extends Rows
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
        return [
            Select::make('login_ads_portal')
                ->title('Portal')
                ->empty('Select a portal...')
                ->options([
                    2 => 'Local Admin',
                    3 => 'Student',
                    4 => 'Vendor'
                ]),
            
            Button::make('Filter')
                ->icon('filter')
                ->type(Color::DEFAULT())
                ->method('filterLoginAds')
        ];
    }
}
