<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Orchid\Screen\Screen;

class ViewPromfluencerDetailedScreen extends Screen
{
    public $promfluencer;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Promfluencer $promfluencer): iterable
    {
        return [
            'promfluencer' => $promfluencer
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'ViewPromfluencerDetailedScreen';
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
        return [];
    }
}
