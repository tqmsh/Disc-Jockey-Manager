<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\NoPlaySong;
use App\Orchid\Layouts\UserSongRequestsLayout;
use App\Orchid\Layouts\ViewSongsLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Models\Song;
use App\Models\SongRequest;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class CreateSongRequestScreen extends Screen
{
    public Events $event;

    public function name(): ?string
    {
        return 'Request a Song for ' . $this->event->event_name;
    }

    public string $description = "Browse songs, and create and delete song requests.";

    public function query(Request $request, Events $event)
    {
        $filters = $request->get('filter');
        $noPlaySongIds = NoPlaySong::where('event_id', $event->id)
            ->pluck('song_id');

        $songRequestIds = SongRequest::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->pluck('song_id');

        $userSongRequests = Song::whereIn('id', $songRequestIds)
            ->filter($filters)
            ->paginate(10);

        $songs = Song::whereNotIn('id', $noPlaySongIds)
            ->whereNotIn('id', $songRequestIds)
            ->filter($filters)
            ->paginate(10);

        return [
            "event" => $event,
            "songs" => $songs,
            "userSongRequests" => $userSongRequests
        ];
    }

    public function commandBar()
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.songs.list', $this->event),
            Button::make('Request Selected Songs')
                ->method('chooseSong')
                ->icon('plus'),
            Button::make('Remove Selected Song Requests')
                ->method('removeSelectedRequests')
                ->icon('trash'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Input::make('filter.title')
                        ->title('Title')
                        ->value(request()->get('filter')['title'] ?? '')
                        ->placeholder('Filter by title'),
                    Input::make('filter.artists')
                        ->title('Artists')
                        ->value(request()->get('filter')['artists'] ?? '')
                        ->placeholder('Filter by artists'),
                    Select::make('filter.explicit')
                        ->title('Explicit')
                        ->options([
                            'Any' => 'Any',
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ])
                        ->value(request()->get('filter')['explicit'] ?? '')
                        ->placeholder('Filter by explicit'),
                ]),
                Group::make([
                    Button::make('Filter')
                        ->method('applyFilters')
                        ->icon('filter'),
                    Button::make('Clear Filters')
                        ->method('clearFilters')
                        ->icon('close'),
                ])
            ]),
            UserSongRequestsLayout::class,
            ViewSongsLayout::class,
        ];
    }

    public function applyFilters(Request $request, Events $event)
    {
        $filterParams = $request->only(['filter.title', 'filter.artists', 'filter.explicit']);
        return redirect()->route('platform.songs.request', ['event' => $event->id, 'filter' => $filterParams['filter']]);
    }

    public function clearFilters(Events $event)
    {
        return redirect()->route('platform.songs.request', ['event' => $event->id]);
    }

    public function chooseSong(Request $request, Events $event)
    {
        try {
            $selectedSongs = $request->input('selectedSongs', []);

            if (empty($selectedSongs)) {
                Toast::warning("You have not selected any songs to request");
                return;
            }

            foreach ($selectedSongs as $songId) {
                $song = Song::find($songId);

                if (NoPlaySong::where("song_id", $song->id)->where('event_id', $event->id)->exists()) {
                    continue;
                }

                $formFields = [
                    'user_id' => Auth::id(),
                    'song_id' => $song->id,
                    'event_id' => $event->id,
                ];

                SongRequest::create($formFields);
            }
            Toast::success('Song requests successfully created.');
        } catch (Exception $e) {
            Toast::error('An error occurred, try again later.');
        }
    }

    public function removeSelectedRequests(Request $request)
    {
        try {
            $selectedSongs = $request->input('selectedUserSongRequests', []);

            if (empty($selectedSongs)) {
                Toast::warning("You have not selected any songs requests to delete.");
                return;
            }

            SongRequest::whereIn('song_id', $selectedSongs)
                ->where('user_id', Auth::id())
                ->delete();
            Toast::success('Song requests removed successfully');
        } catch (Exception $e) {
            Toast::error('An error occurred, try again later.');
        }
    }
}
