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
use App\Orchid\Layouts\ViewSongsLayout;
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

class ViewSongsScreen extends Screen
{

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return ['songs' => Song::latest('songs.created_at') -> paginate(10)];
    }
    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Songs';
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
                ->route('platform.songs.list'),

            ModalToggle::make('Add New Song')
                ->modal('createSongModal')
                ->method('create')
                ->icon('plus'),

            Button::make('Delete Song')
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

          ViewSongsLayout::class,

          Layout::modal('createSongModal', Layout::rows([

            Input::make('song.title')
                ->title('Title')
                ->placeholder('Song Title'),

            Input::make('song.artist')
                ->title('Artist')
                ->placeholder('Song Artist'),

        ]))
        ->title('Create Song')
        ->applyButton('Add Song'),


        Layout::modal('editSongModal', Layout::rows([

            Input::make('song.title')
                ->title('Title')
                ->placeholder('Song Title'),

            Input::make('song.artist')
                ->title('Artist')
                ->placeholder('Song Artist'),

        ]))
        ->title('Edit Song')
        ->applyButton('Update Song'),

        ];
    }

    
    public function create(Request $request)
    {
        try{
        $title= $request->input('song.title'); $artist = $request->input('song.artist');
            if(!Song::where(['title'=> $title, 'artist'=> $artist]) -> exists()){
                $song = new Song();
                $song -> title = $title;
                $song-> artist = $artist;
                $song->save();
            }
            else{
                Toast::warning('This song has been created previously');
            }
        }
        catch(Exception $e){
            Toast::error('There was a error trying to create this Song. Error Message: ' . $e);
        }
    }

    public function delete(Request $request)
    {   
        $songs = $request->get('songs');
        try{
            if(!empty($songs)){
                foreach($songs as $song){
                    Song::where('id', $song)->delete();
                }
                Toast::success('Selected Song(s) deleted succesfully');
            }else{
                Toast::warning('Please select Songs in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected Songs. Error Message: ' . $e);
        }
    }

    public function edit(Request $request)
    {
        try{
            $song = Song::find($request->get("song_id"));
            $song ->title = $request->input('song.title');
            $song-> artist = $request->input('song.artist');
            $song->save();
        }
        catch(Exception $e){
            Toast::error('There was a error trying to update this Song. Error Message: ' . $e);
        }

    }



}   