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
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
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

                Input::make('song.title')
                ->title('Song Title')
                ->type('text')
                ->required()
                ->horizontal()
                ->value($this->noPlaySong->title),

                Input::make('song.artist')
                ->title('Song Artist')
                ->type('text')
                ->horizontal()
                ->value($this->noPlaySong->artist),

            ])
        ];
    }

    public function create(Request $request){

        $noPlaySong= new NoPlaySong();
        $noPlaySong -> title= $request->input('song.title'); $noPlaySong -> artist= $request->input('song.artist');
        $noPlaySong -> save();

        Alert::info('You have successfully created a No Play Song.');

    }
}
