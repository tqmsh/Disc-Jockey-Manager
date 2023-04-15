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
use App\Orchid\Layouts\ViewSongRequestLayout;
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
                    ViewEventLayout::class
                ],
                'Your Registered Events' => [
                    ViewRegisteredEventLayout::class
                ],
            ]),
        ];
    }

    public function redirect($event_id, $type){

        if($type == 'table'){
            return redirect()->route('platform.event.tables', $event_id);
        }   
        else if($type == 'songs'){
            return redirect()->route('platform.songs.list', $event_id);
        }

        return redirect()->route('platform.event.register', $event_id);   
    }
}
