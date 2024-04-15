<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;

class ViewPromfluencerScreen extends Screen
{
    public $promfluencer;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'promfluencer' => Promfluencer::firstWhere('user_id', Auth::id()),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Promfluence';
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
