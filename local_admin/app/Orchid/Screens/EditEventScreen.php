<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use App\Models\Song;
use App\Models\NoPlaySong;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\Localadmin;
use Orchid\Support\Color;
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
use App\Orchid\Layouts\ViewNoPlaySongsLayout;

class EditEventScreen extends Screen
{
    public $event;
    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'noPlaySongs'=> NoPlaySong::where('event_id', $event-> id) ->paginate(10),
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
        return 'Edit Event: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Event')
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
        $this->school = School::find($this->event->school_id);
        
        abort_if($this->event->school_id != Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'), 403);

        
        return [
            
            Layout::rows([

                Input::make('event_name')
                    ->title('Event Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_name),

                DateTimer::make('event_start_time')
                    ->title('Event Start')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_start_time),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_finish_time),
                    
                Input::make('event_address')
                    ->title('Event Address')
                    ->type('text')
                    ->horizontal()
                    ->value($this->event->event_address),
                    
                Input::make('event_zip_postal')
                    ->title('Event Zip/Postal')
                    ->type('text')
                    ->horizontal()
                    ->value($this->event->event_zip_postal),

                Input::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->horizontal()
                    ->value($this->event->event_info),

                Input::make('event_rules')
                    ->title('Event Rules')
                    ->type('text')
                    ->horizontal()
                    ->value($this->event->event_rules),

                Select::make('venue_id')
                    ->title('Venue')
                    ->fromQuery(Vendors::query()->where('category_id', Categories::where('name', 'LIKE', '%'. 'Venue' . '%')->first()->id), 'company_name')
                    ->horizontal()
                    ->value($this->event->venue_id),
            ]),
            
            Layout::tabs([
                "No Play Song List"=>[ViewNoPlaySongsLayout::class, 
                Layout::rows([
                    Button::make('Delete Song')
                    ->icon('trash')
                    ->method('deleteSong'),
                ])],
                "Add No Play Song" =>[
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
                    ])], 
                ]),            
        ];
    }
    
    public function chooseSong(Request $request, Events $event){
        $song = Song::find($request->input('song.id'));
        $formFields = $request->all();
        $formFields['song_id'] = $song->id;
        $formFields['event_id'] = $event -> id;
        NoPlaySong::create($formFields);
    }

    public function deleteSong(Request $request)
    {   
        $noPlaySongs = $request->get('noPlaySongs');
        try{
            if(!empty($noPlaySongs)){
                foreach($noPlaySongs as $noPlaySong){
                    noPlaySong::where('id', $noPlaySong)->delete();
                }
                Toast::success('Selected No Play Song (s) deleted succesfully');
            }else{
                Toast::warning('Please select No Play Songs in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected No Play Songs. Error Message: ' . $e);
        }
    }

    public function update(Events $event, Request $request)
    {
        try{

            $eventsFields = $request->all();

            $event->update($eventsFields);

            Toast::success('You have successfully updated ' . $request->input('event_name') . '.');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(Events $event)
    {
        try{

            $event->delete();

            Toast::success('You have successfully deleted the event.');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this event. Error Code: ' . $e);
        }     
    }
}
