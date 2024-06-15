<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

use function PHPSTORM_META\map;

class CreateLoginAd extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title = 'Create Login Ad';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Select::make('portal')
                ->title('Portal')
                ->empty('Select a portal...')
                ->options([
                    2 => 'Local Admin',
                    3 => 'Student',
                    4 => 'Vendor'
                ]),
                
            Input::make('title')
                ->title('Title')
                ->placeholder('e.g. Prom Committee Expo'),

            Input::make('subtitle')
                ->title('Subtitle')
                ->placeholder('e.g. Where Prom Committees get the best seminars and deals!'),

            Input::make('button_title')
                ->title('Button Title')
                ->placeholder('e.g. Visit the Site NOW'),

            Input::make('website')
                ->title('Website URL')
                ->placeholder('e.g. https://promcommitteeexpo.com/'),
            
            Input::make('image')
                ->title('Background Image URL')
                ->placeholder('e.g. https://www.simplilearn.com/ice9/free_resources_article_thumb/what_is_image_Processing.jpg'),

            Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createLoginAd')
        ];
    }
}
