<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\SongRequest;
use App\Models\Student;
use App\Models\Song;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\ModalToggle;

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

            TD::make('event_name', 'Request Title')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::find($songRequest -> song_id) -> title);
                }),

            TD::make('event_name', 'Request Artist')
                ->render(function (SongRequest $songRequest) {
                    return e(Song::find($songRequest -> song_id) -> artist);
                }),

             TD::make('event_name', 'Requester ID')
                ->render(function (SongRequest $songRequest) {
                    return e($songRequest -> requester_user_id);
                }),

            TD::make()
                ->render(function (SongRequest $songRequest) {
                    return Button::make('Edit')-> type(Color::PRIMARY())-> method('redirect', ['songReq' => $songRequest])->icon('pencil');
                }),
        ];
    }


}