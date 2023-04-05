<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Song;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewSongsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'songs';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            
            TD::make()
                ->render(function (Song $song){
                    return CheckBox::make('songs[]')
                        ->value($song->id)
                        ->checked(false);
                }),

            TD::make('title', 'Song Title')
                ->render(function (Song $song) {
                    return Link::make($song->title)
                        ->route('platform.song.edit', $song);
                }),
            TD::make('artist', 'Song Artist')
                ->render(function (Song $song) {
                    return Link::make($song->artist)
                        ->route('platform.song.edit', $song);
                }),

            TD::make('num_votes', 'Song Number of Votes')
                ->render(function (Song $song) {
                    return Link::make($song-> num_votes)
                        ->route('platform.song.edit', $song);
                }),
            TD::make()
                ->render(function (Song $song) {
                    return Button::make('Edit')-> type(Color::PRIMARY())-> method('redirect', ['song_id'=>$song->id, 'type'=>"edit"])->icon('pencil');
                }),
        ];    
    }
}