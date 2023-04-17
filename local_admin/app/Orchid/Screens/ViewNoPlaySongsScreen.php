<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use App\Models\SongRequest;
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
use App\Models\NoPlaySong;
use App\Orchid\Layouts\ViewNoPlaySongsLayout;

class ViewNoPlaySongsScreen extends Screen
{

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'noPlaySongs' => NoPlaySong::where('event_id', $event -> id)->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View No Play Songs';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Song')
                ->icon('plus')
                ->route('platform.noplaysong.create'),

            Button::make('Delete Selected Songs')
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete the selected songs?')),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.noplaysong.list')
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
            ViewNoPlaySongsLayout::class,
        ];
    }


    public function delete(Request $request)
    {   
        $noPlaySongs = $request->get('noPlaySongs');
        
        try{
            if(!empty($noPlaySongs)){
                foreach($noPlaySongs as $noPlaySong){
                    NoPlaySong::where('id', $noPlaySong)->delete();
                }
                Toast::success('Selected song deleted succesfully');

            return redirect()->route('platform.noplaysong.list');

            }else{
                Toast::warning('Please select songs in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected songs. Error Message: ' . $e);
        }
    }
}