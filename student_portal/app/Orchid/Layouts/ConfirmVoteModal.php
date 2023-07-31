<?php

namespace Orchid\Screen\Layouts;

use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Toast;

/**
 * Button commands.
 *
 * @return \Orchid\Screen\Action[]
 */
public function commandBar(): array
{
    return [
        ModalToggle::make('Launch demo modal')
            ->modal('exampleModal')
            ->method('action')
            ->icon('full-screen'),
    ];
}

/**
 * Views.
 *
 * @return string[]|\Orchid\Screen\Layout[]
 */
public function layout(): array
{
    return [
        Layout::modal('exampleModal', [
            Layout::rows([]),
        ]),
    ];
}

/**
 * The action that will take place when
 * the form of the modal window is submitted
 */
public function action(): void
{
    Toast::info('Hello, world! This is a toast message.');
}
