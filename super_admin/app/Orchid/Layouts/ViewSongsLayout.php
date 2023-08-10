<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use App\Models\Song;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Color;

class ViewSongsLayout extends Table
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
                    if ($song->status == 0) return 'Unknown';
                    return $song->explicit ? 'Yes' : 'No';
                }),

            TD::make('status', 'Status')
                ->filter()
                ->render(function (Song $song) {
                    return $song->status ? 'Approved' : 'Pending';
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Song $song) {
                    return Link::make('Edit')
                        ->route('platform.songs.edit', $song->id)
                        ->type(Color::PRIMARY())
                        ->icon('eye');
                })
        ];
    }
}
