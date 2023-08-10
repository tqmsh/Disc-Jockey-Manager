<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewSongRequestLayout;
use App\Models\SongRequest;
use App\Models\EventAttendees;

class ViewSongRequestScreen extends Screen
{
    public Events $event;

    public function name(): ?string
    {
        return 'Song Request Data for ' . $this->event->event_name;
    }

    public string $description = 'View data on song requests made by all attendees of this event.';

    public function query(Events $event): iterable
    {
        $studentAttendee = EventAttendees::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        abort_if(!($studentAttendee->exists() && $studentAttendee->ticketstatus == 'Paid'), 403);

        $songRequests = SongRequest::where('event_id', $event->id)
            ->select('song_requests.song_id', 'song_requests.event_id', DB::raw('COUNT(song_requests.user_id) as num_requesters'))
            ->groupBy('song_requests.song_id', 'song_requests.event_id')
            ->orderBy('num_requesters', 'desc')
            ->paginate(10);

        return [
            'songRequests' => $songRequests,
            'event' => $event
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list'),
            Link::make('Request a Song')
                ->icon('plus')
                ->route('platform.songs.request', ['event' => $this->event->id])
        ];
    }

    public function layout(): iterable
    {
        return [
            ViewSongRequestLayout::class,
        ];
    }
}
