<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Localadmin;
use App\Orchid\Layouts\ViewLocaladminLayout;

class ViewLocaladminScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'Localadmin' => Localadmin::paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Local Admin List';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewLocaladminLayout::class
        ];
    }
}
