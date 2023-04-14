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
use App\Models\Song;
use App\Models\SongRequest;
use App\Models\NoPlaySong;
use Orchid\Screen\TD;   

class ViewSongRequestScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return ['songRequests' => SongRequest::where('event_id', $event-> id)-> latest('events.created_at') ->paginate(10),];
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
            Select::make('song.title')
            ->fromModel(Song::class, 'title')
            ->displayAppend('full')
            -> empty('Choose a song'),  

            Button::make('Submit')
            ->type(Color::PRIMARY())
            ->method('chooseSong')
            ->icon('plus')
            

            ])
        ];
    }

    public function requestSong(Request $request){
        // Validate form data, save song to database, etc.
        $request->validate(['song.title' => 'required|max:255',]);
        $title= $request->input('song.title'); $artist= $request->input('song.artist');

        if (NoPlaySong::where(['title'=> $title, 'artist' => $artist])->exists()) {
            return Alert::error('Please Try Again.');
         }
         else{
            $songRequest= new SongRequest();
            $songRequest->title = $request->input('song.title');
            $songRequest->artist = $request->input('song.artist');
            $songRequest->event_id= $request->get("event_id");
            $songRequest->requester_user_id= $request->user()-> id;
            $songRequest-> save();
         }
    }

    public function chooseSong(Request $request){
        $request->validate(['song.title' => 'required|max:255']);
        $song = Song::where($request->input('song.title'));

        Alert::info($song -> id);
     //   $songRequest= new SongRequest();
       // $songRequest ->song_id= $song ->id; 

    }


}