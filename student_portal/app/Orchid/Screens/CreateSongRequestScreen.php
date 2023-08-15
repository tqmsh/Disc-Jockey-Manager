<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\NoPlaySong;
use App\Orchid\Layouts\ViewUserSongRequestsLayout;
use App\Orchid\Layouts\ViewSongsLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
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
        return 'Request a Song: ' . $this->event->event_name;
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
            ->where('status', '1')
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
                ->confirm('Are you sure you want to remove your selected song requests?')
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
                            'Unknown' => 'Unknown',
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
            ViewUserSongRequestsLayout::class,
            ViewSongsLayout::class,
            Layout::modal('customSongModal', [
                Layout::rows([
                    Input::make('customSong.title')
                        ->title('Title')
                        ->placeholder('Enter song title'),
                    Input::make('customSong.artists')
                        ->title('Artists')
                        ->placeholder('Enter artists'),
                ]),
            ])->title("Create a Custom Song Request"),
            Layout::rows([
                ModalToggle::make("Can't find a song? Create a custom song request!")
                    ->modal('customSongModal')
                    ->type(Color::PRIMARY())
                    ->method("createCustomSongRequest")
            ]),
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

                if (NoPlaySong::where("song_id", $song->id)
                    ->where('event_id', $event->id)
                    ->exists()) {
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

    public function createCustomSongRequest(Request $request, Events $event)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customSong.title' => 'required|max:255',
                'customSong.artists' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $customSongData = $request->get('customSong');
            $title = $customSongData['title'];
            $artists = $customSongData['artists'];

            $existingSong = Song::where('title', $title)->where('artists', $artists)->first();

            if ($existingSong) {
                if (NoPlaySong::where("song_id", $existingSong->id)
                        ->where('event_id', $event->id)
                        ->exists() && $existingSong->status == 1) {
                    Toast::error("This song has been banned by the event organizer.");
                    return back()->withInput();
                }

                $existingRequest = SongRequest::where('song_id', $existingSong->id)
                    ->where('event_id', $event->id)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($existingRequest) {
                    Toast::warning("You have already requested this song.");
                    return back()->withInput();
                }

                SongRequest::create([
                    'user_id' => Auth::id(),
                    'song_id' => $existingSong->id,
                    'event_id' => $event->id,
                ]);
            } else {
                $newSong = Song::create([
                    'title' => $title,
                    'artists' => $artists,
                    'explicit' => false,
                    'status' => 0,
                ]);

                SongRequest::create([
                    'user_id' => Auth::id(),
                    'song_id' => $newSong->id,
                    'event_id' => $event->id,
                ]);
            }

            Toast::success('Song request successfully created.');
        } catch (Exception $e) {
            Toast::error('An error occurred, try again later.');
        }
        return back();
    }
}
