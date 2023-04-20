<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\School;
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
use App\Models\SongRequest;
use App\Models\NoPlaySong;
use Orchid\Support\Color;

class CreateNoPlaySongScreen extends Screen
{
    public $event;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return ['event' => $event];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add New No Play Song';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('create'),

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

                Button::make('Submit')
                ->type(Color::PRIMARY())
                ->method('create')
                ->icon('plus')
            ])
        ];
    }

    public function create(Request $request, Events $event){
        $request->validate(['song.id' => 'required|max:255']);
        $song = Song::find($request->input('song.id'));
        $formFields = $request->all();
        $formFields['song_id'] = $song->id;
        $formFields['event_id'] = $event -> id;
        NoPlaySong::create($formFields);
 
    }
}
