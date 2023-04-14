<?php

namespace App\Orchid\Layouts;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Song;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

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
            TD::make('event_name', 'Request Title')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::where($songRequest -> song_id) -> title);
                }),

            TD::make('event_name', 'Request Artist')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::where($songRequest -> song_id) -> artist);
                }),

             TD::make('event_name', 'Requester ID')
                ->render(function (SongRequest $songRequest) {
                    return e($songRequest -> requester_id);
                }),
            
        ];    
    }
}