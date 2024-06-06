<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\Song;
use App\Models\SongRequest;
use App\Orchid\Layouts\ViewSongRequestsLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewSongRequestsScreen extends Screen
{
    public Events $event;

    public function name(): string
    {
        return 'Song Requests: ' . $this->event->event_name;
    }

    public ?string $description = "View and modify song requests for this event.";

    public function query(Events $event): iterable
    {
        $songRequests = SongRequest::where('event_id', $event->id)
            ->select('song_requests.song_id', 'song_requests.event_id', DB::raw('COUNT(song_requests.user_id) as num_requesters'))
            ->groupBy('song_requests.song_id', 'song_requests.event_id')
            ->orderBy('num_requesters', 'desc')
            ->paginate(min(request()->query('pagesize', 10), 100));

        return [
            'event' => $event,
            'songRequests' => $songRequests,
        ];
    }


    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list'),
            Link::make('Banned Songs')
                ->icon('ban')
                ->route('platform.bannedSongs.list', $this->event),
            Button::make('Delete Selected Song Requests')
                ->icon('trash')
                ->confirm('Are you sure you want to delete the selected song requests?')
                ->method('delete'),
        ];
    }

    public function layout(): iterable
    {
        /** @noinspection PhpParamsInspection */
        return [
            Layout::modal('editSong',
                Layout::rows([
                    Select::make('song.id')
                        ->options(function () {
                            return Song::whereDoesntHave('noPlaySongs', function ($query) {
                                $query->where('event_id', $this->event->id);
                            })
                                ->where('status', 1)
                                ->pluck('title', 'id')
                                ->map(function ($title, $id) {
                                    return $title . ' - ' . Song::find($id)->artists;
                                })->all();
                        })
                        ->empty('Choose a song')
                        ->required('You must select a song.')
                ]))
                ->applyButton('Save Request')
                ->type(Color::PRIMARY()),
            ViewSongRequestsLayout::class,
        ];
    }

    public function redirect(Events $event)
    {
        return redirect()
            ->route('platform.songRequesters.list',
                [
                    'event_id' => $event->id,
                    'song_id' => request('song_id')
                ]
            );
    }


    public function updateSong(Request $request, Events $event)
    {
        $prevSongId = $request->get('prevSongId');
        $songId = $request->input('song.id');

        if ($prevSongId == $songId) {
            Toast::warning("No changes made");
            return;
        }

        $songRequestsToUpdate = SongRequest::where('event_id', $event->id)
            ->where('song_id', $prevSongId)
            ->get();

        foreach ($songRequestsToUpdate as $songRequest) {
            // Check if a song request with the new song ID already exists
            $existingRequest = SongRequest::where('event_id', $event->id)
                ->where('song_id', $songId)
                ->where('user_id', $songRequest->user_id)
                ->first();

            // If not, update the song request
            if (!$existingRequest) {
                $songRequest->song_id = $songId;
                $songRequest->save();
            } else {
                $songRequest->delete();
            }
        }
        Toast::success("Song requests for " . Song::find($prevSongId)->title . " changed to " . Song::find($songId)->title);
    }

    public function delete(Request $request, Events $event)
    {
        $songs = $request->get('selectedSongRequests');
        try {
            if (!empty($songs)) {
                foreach ($songs as $song) {
                    SongRequest::where('song_id', $song)
                        ->where('event_id', $event->id)
                        ->delete();
                }
                Toast::success('Selected song requests deleted successfully');
            } else {
                Toast::warning('No songs requests were selected');
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to delete the selected song requests. Error Message: ' . $e->getMessage());
        }
    }
}
