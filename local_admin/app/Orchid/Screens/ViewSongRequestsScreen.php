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
use App\Orchid\Layouts\ViewSongRequestsLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use App\Models\User;
use App\Models\Song;
use App\Models\SongRequest;
use App\Models\NoPlaySong;
use Orchid\Screen\TD;   

class ViewSongRequestsScreen extends Screen
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
                ->route('platform.event.list'),

            Button::make('Delete Song Request')
                ->icon('trash')
                ->method('delete'),
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
            Layout::modal('editSong', Layout::rows([
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
            ]))
            ->title('Create Task')
            ->applyButton('Add Task'),

          ViewSongRequestsLayout::class,
        ];
    }

    public function delete(Request $request)
    {   
        $songReqs = $request->get('songRequests');
        try{
            if(!empty($songReqs)){
                foreach($songReqs as $songReq){
                    SongRequest::where('id', $songReq)->delete();
                }
                Toast::success('Selected Song Request(s) deleted succesfully');
            }else{
                Toast::warning('Please select Song Requests in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected Song Requests. Error Message: ' . $e);
        }
    }

    public function update(Request $request)
    {
        try{
            $songRequest= songRequest::find($request->get('songReq'));
            $song = Song::find($request->input('song.id'));
            $songRequest->song_id= $song->id; 
            $songRequest -> save();

        }catch(Exception $e){
            Alert::error('There was an error editing this Request. Error Code: ' . $e->getMessage());
        }
    }


}   