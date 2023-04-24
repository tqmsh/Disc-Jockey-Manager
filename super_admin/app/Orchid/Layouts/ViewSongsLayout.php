<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Song;
use App\Models\Student;
use App\Models\User;
use App\Models\NoPlaySong;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;

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
                        ->value($song -> id)
                        ->checked(false);
                }),

            TD::make('song_title', 'Song Title')
                ->render(function (Song $song) {
                    return  $song-> title;
                }),

            TD::make('song_artist', 'Song Artist')
                ->render(function (Song $song) {
                    return $song -> artist;
                }),

            TD::make()
                ->render(function (Song $song) {
                    return ModalToggle::make('Edit')
                        ->icon('microphone')         
                        ->modal('editSongModal')
                        ->modalTitle('Edit Song')
                        ->type(Color::DARK())
                        ->method("edit", ['song_id' => $song -> id]);
                }),
        ];
    }
}