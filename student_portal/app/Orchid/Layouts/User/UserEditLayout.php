<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Cropper;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.firstname')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('First Name'))

                ->placeholder(__('User Name')),

            Input::make('user.lastname')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Last Name'))
                ->placeholder(__('User Name')),

            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('User Name'))
                ->placeholder(__('User Name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),
                
            Cropper::make("user.pfp")
                ->storage("s3")
                ->title("Profile Picture")
                ->width(300)
                ->height(300)
                ->help("This image will be displayed on your profile.")
                ->horizontal()
                ->acceptedFiles('.png, .jpg, .jpeg,')
                ->value(auth()->user()->pfp) 
        ];
    }
}
