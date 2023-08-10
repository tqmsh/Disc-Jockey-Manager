<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use App\Models\SongRequest;
use App\Models\Song;

class ViewSongRequestLayout extends Table
{
    protected $target = 'songRequests';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('request_title', 'Title')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::find($songRequest->song_id)->title);
                }),

            TD::make('request_artist', 'Artist')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::find($songRequest->song_id)->artists);
                }),

            TD::make('num_requesters', 'Number of Requesters')
                ->render(function (SongRequest $songRequest) {
                    return e($songRequest->num_requesters);
                }),
        ];
    }
}
