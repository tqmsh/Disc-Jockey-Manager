<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\SongRequest;
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

class EditSongRequestsScreen extends Screen
{
    public $songRequest;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(SongRequest $songRequest): iterable
    {
        return [
            'songRequest' => $songRequest
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
                ->route('platform.songreq.list')
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
                    ->title('Song Request Title')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->songRequest->title),

                Input::make('song.artist')
                    ->title('Song Request Artist')
                    ->type('text')
                    ->horizontal()
                    ->value($this->songRequest->artist),
            ]),
        ];
    }

    public function update(SongRequest $songRequest, Request $request)
    {
        try{

            $title= $request->input('song.title'); $artist= $request->input('song.artist');

            $songRequest->title= $title; $songRequest->artist= $artist; 

            return redirect()->route('platform.songreq.list');

        }catch(Exception $e){

            Alert::error('There was an error editing this Request. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(SongRequest $songRequest)
    {
        try{
            $songRequest->delete();

            Toast::success('You have successfully deleted the song request.');

            return redirect()->route('platform.songreq.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this Request. Error Code: ' . $e);
        }     
    }
}
