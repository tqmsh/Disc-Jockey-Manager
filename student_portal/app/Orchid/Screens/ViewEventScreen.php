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
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;
use App\Models\Song;
use App\Models\SongRequest;
use App\Models\NoPlaySong;
use Orchid\Screen\TD;   

class ViewEventScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $registered_event_ids = EventAttendees::where('user_id', Auth::user()->id)->get('event_id')->toArray();

        $registered_event_ids = Arr::pluck($registered_event_ids, ['event_id']);

        return [
            'events' => Events::where('school_id', Student::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->latest('events.created_at')->paginate(10),
            'registered_events' => Events::whereIn('id', $registered_event_ids)->latest('events.created_at')->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Events';
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
                'All Events' => [

                    Layout::table('allEvents',[
                        TD::make()
                        ->width('100px')
                        ->align(TD::ALIGN_RIGHT)
                        ->render(function($event){
                            return Button::make('Register')->type(Color::PRIMARY())->method('redirect', ['event_id' => $event->id])->icon('plus');
                        }), 
        
                        TD::make('event_name', 'Event Name')
                            ->render(function (Events $event) {
                                return e($event->event_name);
                            }),
                        TD::make('event_start_time', 'Event Start Date')
                            ->render(function (Events $event) {
                                return e($event->event_start_time);
                            }),
                        TD::make('school', 'School')
                            ->render(function (Events $event) {
                                return e($event->school);
                            }),
                        TD::make('event_address', 'Event Address')
                            ->render(function (Events $event) {
                                return e($event->event_address);
                            }),
                        TD::make('event_zip_postal', 'Event Zip/Postal')
                            ->render(function (Events $event) {
                                return e($event->event_zip_postal);
                            }),
                        TD::make('event_info', 'Event Info')
                            ->render(function (Events $event) {
                                return e($event->event_info);
                            }),
            
                        TD::make('event_rules', 'Event Rules')
                            ->render(function (Events $event) {
                                return e($event->event_rules);
                            }),

                        TD::make('song_submissions', 'Choose a Song')
                            ->render(function (Events $event) {
                                return ModalToggle::make('Choose')
                                    ->icon('music-tone-alt')         
                                    ->modal('chooseSong')
                                    ->modalTitle('Songs')
                                    ->method('chooseSong')
                                    ->type(Color::PRIMARY());
                        }),
            
                        TD::make('song_requests', 'Request a Song')
                            ->render(function (Events $event) {
                                return ModalToggle::make('Request')   
                                    ->icon('microphone')       
                                    ->modal('requestSong')
                                    ->modalTitle('Songs')
                                    ->method('requestSong', ['event_id' => $event->id])
                                    ->type(Color::PRIMARY());
                        }),
                    ]),
                    
                Layout::modal('chooseSong', Layout::rows([
                        Select::make('song.title')
                        ->fromModel(Song::class, 'title')
                        ->displayAppend('full')
                        -> empty('Choose a song'),  
                    ]))
                    ->title('Choose Song')
                    ->applyButton('Submit Song'),
    
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
                ],
                'Your Registered Events' => [
                    Layout::table('allEvents',[
                    TD::make()
                        ->width('100px')
                        ->align(TD::ALIGN_RIGHT)
                        ->render(function($event){
                            return Button::make('Tables')->type(Color::DARK())->method('redirect', ['event_id' => $event->id, 'type' => 'table'])->icon('table');
                        }), 
                    TD::make()
                        ->width('100px')
                        ->align(TD::ALIGN_RIGHT)
                        ->render(function($event){
                            return Button::make('Unregister')->type(Color::PRIMARY())->method('redirect', ['event_id' => $event->id])->icon('close');
                        }), 
        
                    TD::make('event_name', 'Event Name')
                        ->render(function (Events $event) {
                            return e($event->event_name);
                        }),
                    TD::make('event_start_time', 'Event Start Date')
                        ->render(function (Events $event) {
                            return e($event->event_start_time);
                        }),
                    TD::make('school', 'School')
                        ->render(function (Events $event) {
                            return e($event->school);
                        }),
                    TD::make('event_address', 'Event Address')
                        ->render(function (Events $event) {
                            return e($event->event_address);
                        }),
                    TD::make('event_zip_postal', 'Event Zip/Postal')
                        ->render(function (Events $event) {
                            return e($event->event_zip_postal);
                        }),
                    TD::make('event_info', 'Event Info')
                        ->render(function (Events $event) {
                            return e($event->event_info);
                        }),
        
                    TD::make('event_rules', 'Event Rules')
                        ->render(function (Events $event) {
                            return e($event->event_rules);
                        }),
                    ])
                ],
            ]),

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

    }


    public function redirect($event_id, $type){

        if($type == 'table'){
            return redirect()->route('platform.event.tables', $event_id);
        }

        return redirect()->route('platform.event.register', $event_id);   
    }
}
