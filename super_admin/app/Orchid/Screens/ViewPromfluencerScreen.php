<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ViewPromfluencerScreen extends Screen
{
    public $promfluencers;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'promfluencers' => Promfluencer::all(),
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
        return [
            Layout::table('promfluencers', [
                TD::make('name')
                    ->render(function (Promfluencer $promfluencer) {
                        return $promfluencer->user->firstname . ' ' . $promfluencer->user->lastname;
                    }),
                TD::make('school')
                    ->render(fn (Promfluencer $promfluencer) => $promfluencer->user->student->school),
                TD::make('grade')
                    ->render(fn (Promfluencer $promfluencer) => $promfluencer->user->student->grade),
            ]),
        ];
    }
}
