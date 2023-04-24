<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\NoPlaySong;
use App\Models\Song;
use App\Models\Student;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\ModalToggle;

class ViewNoPlaySongsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */

    protected $target = 'noPlaySongs';

    
    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {

        return [
            TD::make()
                ->render(function (NoPlaySong $noPlaySong){
                    return CheckBox::make('noPlaySongs[]')
                        ->value($noPlaySong -> id)
                        ->checked(false);
                }),

            TD::make('title', 'Song Title')
                ->render(function (NoPlaySong $noPlaySong) {
                    return (Song::find($noPlaySong-> song_id) -> title);
                }),
            TD::make('artist', 'Song Artist')
                ->render(function (NoPlaySong $noPlaySong) {
                    return (Song::find($noPlaySong-> song_id) -> artist);
                }),
                


        ];
    }


}