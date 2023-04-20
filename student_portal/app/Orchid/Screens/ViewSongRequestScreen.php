<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Student;
use Orchid\Screen\Screen;
use Illuminate\Support\Arr;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewEventLayout;
use App\Orchid\Layouts\ViewRegisteredEventLayout;
use App\Orchid\Layouts\ViewSongRequestLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use App\Models\User;
use App\Models\Song;
use App\Models\SongRequest;
use App\Models\NoPlaySong;
use Orchid\Screen\TD;   

class ViewSongRequestScreen extends Screen
{

    public $event;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return ['songRequests' => SongRequest::where('event_id', $event-> id) ->paginate(10),
                'event' => $event];
    }
    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Song Requests';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
        ];
    }
    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
          ViewSongRequestLayout::class,

          Layout::rows([
            Select::make('song.id')
            ->options(function(){
                $arr= array();
                foreach(Song::all() as $song){
                    if(!NoPlaySong::where('song_id', $song -> id)->where('event_id', $this -> event -> id)->exists()){
                        $arr[$song -> id]= $song -> title . '- ' . $song-> artist;
                    }
                }
                return $arr;
            })
            -> empty('Choose a song'), 

            Button::make('Submit')
            ->type(Color::PRIMARY())
            ->method('chooseSong')
            ->icon('plus')
            ]),

        /** 
            Layout::table('songs', [
                TD::make('song_requests', 'Request a Song')
                ->render(ModalToggle::make('Request')   
                        ->icon('microphone')       
                        ->modal('requestSong')
                        ->modalTitle('Songs')
                        ->method('requestSong')
                        ->type(Color::PRIMARY())),
            ]),

            Layout::modal('requestSong', Layout::rows([
                Input::make('song.title')
                    ->title('Title')
                    ->placeholder('Song Title'),

                Input::make('song.artist')
                    ->title('Artist')
                    ->placeholder('Song Artist'),

            ]))
            ->title('Request Song')
            ->applyButton('Submit Song Request'),
            */

        ];
    }

    public function requestSong(Request $request){

        $title= $request->input('song.title'); $artist= $request->input('song.artist');

        if (NoPlaySong::where(['title'=> $title, 'artist' => $artist])->exists()) {
            return Alert::error('Please Try Again.');
         }
         else{
            $songRequest= new SongRequest();
            $songRequest->title = $request->input('song.title');

            $songRequest->event_id= $request->get("event_id");
            $songRequest->requester_user_id= $request->user()-> id;
            $songRequest-> save();
         }
    }
    public function chooseSong(Request $request, Events $event){

        $song = Song::find($request->input('song.id'));
        $formFields = $request->all();
        $formFields['requester_user_id'] = auth()->id();
        $formFields['song_id'] = $song->id;
        $formFields['event_id'] = $event -> id;
        SongRequest::create($formFields);
 
    }


}