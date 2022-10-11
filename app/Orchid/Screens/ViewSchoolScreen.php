<?php

namespace App\Orchid\Screens;

use App\Models\School;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ViewSchoolLayout;

class ViewSchoolScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'schools' => School::paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Schools List';
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
            ViewSchoolLayout::class
        ];
    }
}
