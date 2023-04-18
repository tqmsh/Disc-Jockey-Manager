<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\SongRequest;
use App\Models\School;
use App\Models\Events;
use App\Models\Song;
use App\Models\NoPlaySong;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;

class EditSongRequestsScreen extends Screen
{
    public $songRequest;
    public $event;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(SongRequest $songRequest, Events $event): iterable
    {
        return [
            'songRequest' => $songRequest,
            'event' => $event
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Song Request: ' . $this->songRequest->title . '- ' . $this->songRequest->artist;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit Request')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Request')
                ->icon('trash')
                ->method('delete'),

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
            Layout::rows([

                
                Select::make('song.id')
                ->options(function(){
                    $arr= array();
                    foreach(Song::all() as $song){
                        if(!NoPlaySong::where(['song_id'=> $song -> id])->exists()){
                            $arr[$song -> id]= $song -> title . '- ' . $song-> artist;
                        }
                    }
                    return $arr;
                })
                -> empty('Choose a song'), 


                //Again, should not be Input classes, should be Select classes.
            
        ])
            ];
    }

    public function update(SongRequest $songRequest, Request $request)
    {
        try{
            $request->validate(['song.id' => 'required|max:255']);
            $song = Song::find($request->input('song.id'));
            $songRequest->song_id= $song->id; $songRequest->event_id= $artist; 

            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            Alert::error('There was an error editing this Request. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(SongRequest $songRequest)
    {
        try{
            $songRequest->delete();

            Toast::success('You have successfully deleted the song request.');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this Request. Error Code: ' . $e);
        }     
    }
}
