<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\ViewPackageLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Illuminate\Support\Facades\Auth;

class ViewPackageScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'packages' => Auth::user()->packages
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Your Packages';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Link::make('Create a New Package')
                ->icon('plus')
                ->route('platform.package.create'),

            Button::make('Delete Selected Packages')
                ->icon('trash')
                ->method('deletePackages')
                ->confirm(__('Are you sure you want to delete the selected packages?')),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.package.list')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewPackageLayout::class
        ];
    }
}
