<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

use App\Orchid\Layouts\ViewSongRequestLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Select;
use App\Models\Song;
use App\Models\SongRequest;
use App\Models\NoPlaySong;
use Orchid\Screen\TD;   
use Orchid\Support\Facades\Toast;

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
        return ['songRequests' => SongRequest::where('event_id', $event->id)->paginate(10),
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
            Layout::tabs([
                "Song Request List"=> ViewSongRequestLayout::class,

                "Request a Song" =>[
                    Layout::rows([
                        Select::make('song.id')
                        ->options(function(){
                            $arr= array();
                            foreach(Song::all() as $song){
                                if(!NoPlaySong::where('song_id', $song -> id)->where('event_id', $this -> event -> id)->exists() && 
                                !SongRequest::where('song_id', $song -> id)->where('event_id', $this -> event -> id)->exists()){
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
                        ])], 
                ]),

        ];
    }
    public function chooseSong(Request $request, Events $event){
        try{
            $song = Song::find($request->input('song.id'));
            $formFields = $request->all();
            $formFields['requester_user_id'] = auth()->id();
            $formFields['song_id'] = $song->id;
            $formFields['event_id'] = $event -> id;
            SongRequest::create($formFields);
            Toast::success('Song Request created successfully');
        }
        catch(Exception $e){
            Toast::error('There was a error trying to create the Song Request. Error Message: ' . $e);
        }
 
    }
}