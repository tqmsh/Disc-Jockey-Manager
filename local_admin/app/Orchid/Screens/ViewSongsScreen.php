<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Song;
use App\Models\SongRequest;
use App\Models\School;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewSongsLayout;

class ViewSongsScreen extends Screen
{

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'songRequests' => SongRequest::where('event_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->latest('songs.created_at')->filter(request(['country', 'state_province', 'school', 'school_board']))->paginate(10)
        ];
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

            Button::make('Delete Selected Songs')
                ->icon('trash')
                ->method('deleteSongs')
                ->confirm(__('Are you sure you want to delete the selected songs?')),
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

                Group::make([
                    
                    Select::make('school')
                        ->title('School')
                        ->help('Type in boxes to search')
                        ->empty('No selection')
                        ->fromModel(Events::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(School::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewSongsLayout::class,

            Group::make([
            Link::make('Add New No Play Song')
                ->icon('plus')
                ->route('platform.songs.create'),


            Button::make('Delete Selected No Play Songs')
                ->icon('trash')
                ->confirm(__('Are you sure you want to delete the selected songs?')),
            ])



        ];
    }

    public function filter(){
        return redirect()->route('platform.song.list', request(['school', 'country', 'school_board', 'state_province']));
    }

    public function deleteSongs(Request $request)
    {   
        //get all localadmins from post request
        $songs = $request->get('songs');
        
        try{

            //if the array is not empty
            if(!empty($songs)){

                //loop through the songs and delete them from db
                foreach($songs as $song){
                    Songs::where('id', $song)->delete();
                }

                Toast::success('Selected songs deleted succesfully');

            }else{
                Toast::warning('Please select songs in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected songs. Error Message: ' . $e);
        }
    }

    public function redirect($song, $type){
        if($type == 'song'){
            return redirect()->route('platform.songs.list', $song);
        } 
        else if($type == 'edit'){
            return redirect() -> route('platform.songs.edit', $song);
        }
        else {
            return redirect()->route('platform.songs.list', $song);
        }    
    }
}