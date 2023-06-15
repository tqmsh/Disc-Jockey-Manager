<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\SongRequest;
use App\Models\Student;
use App\Models\Song;
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

class ViewSongRequestsLayout extends Table
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
            TD::make()
                ->render(function (SongRequest $songRequest){
                    return CheckBox::make('songRequests[]')
                        ->value($songRequest -> id)
                        ->checked(false);
                }),
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
                return e(count(json_decode($songRequest-> requester_user_ids, TRUE)));
            }),

            TD::make()
                ->render(function (SongRequest $songRequest) {
                    return Button::make('View Requesters')-> type(Color::PRIMARY())-> method('redirect', ["songReq_id" => $songRequest -> id])->icon('people');
                }),

            TD::make()
                ->render(function (SongRequest $songRequest) {
                    return ModalToggle::make('Change')
                        ->icon('microphone')         
                        ->modal('editSong')
                        ->modalTitle('Change Song')
                        ->type(Color::PRIMARY())
                        ->method("update", ['songReq' => $songRequest -> id]);
                }),
        ];
    }
}