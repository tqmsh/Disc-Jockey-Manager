<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Song;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;

class ViewSongsLayoutNoEdit extends Table
{

    protected $target = 'songs';

    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (Song $song) {
                    return CheckBox::make('selectedSongs[]')
                        ->value($song->id)
                        ->checked(false);
                }),

            TD::make('title', 'Title')
                ->filter()
                ->render(function (Song $song) {
                    return $song->title;
                }),

            TD::make('artists', 'Artist(s)')
                ->filter()
                ->render(function (Song $song) {
                    return $song->artists;
                }),

            TD::make('explicit', 'Explicit')
                ->filter()
                ->render(function (Song $song) {
                    return $song->explicit ? 'Yes' : 'No';
                }),

        ];
    }
}
