<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use App\Models\Song;
use Orchid\Support\Color;

class ViewSongsLayout extends ViewSongsLayoutNoEdit
{
    protected $target = 'songs';

    protected function columns(): iterable
    {
        $columns = parent::columns();
        $columns[] = TD::make('actions', 'Actions')
            ->align(TD::ALIGN_CENTER)
            ->width('100px')
            ->render(function (Song $song) {
                return Link::make('Edit')
                    ->route('platform.songs.edit', $song->id)
                    ->type(Color::PRIMARY())
                    ->icon('eye');
            });
        return $columns;
    }
}
