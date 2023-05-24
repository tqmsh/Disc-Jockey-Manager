<?php

namespace App\Orchid\Layouts;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Song;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use App\Models\SongRequest;
use App\Models\User;

class ViewSongRequestLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
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
                    return e(Song::find($songRequest -> song_id) -> title);
                }),

            TD::make('request_artist', 'Artist')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::find($songRequest -> song_id) -> artist);
                }),

            TD::make('num_requesters', 'Number of Requesters')
                ->render(function (SongRequest $songRequest) {
                    return e(count(json_decode($songRequest-> requester_user_ids)));
                }),
            
        ];    
    }
}