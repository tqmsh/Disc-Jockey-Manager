<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\NoPlaySong;
use App\Models\Song;
use App\Models\SongRequest;
use App\Orchid\Layouts\ViewSongsLayoutNoEdit;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CreateBannedSongsScreen extends Screen
{
    public Events $event;

    public function name(): ?string
    {
        return 'Ban Songs for ' . $this->event->event_name;
    }

    public string $description = "Select songs to ban.";

    public function query(Request $request, Events $event): iterable
    {
        $filters = $request->get('filter');

        $noPlaySongIds = NoPlaySong::where('event_id', $event->id)->pluck('song_id');

        $songs = Song::filter($filters)
            ->whereNotIn('id', $noPlaySongIds)
            ->where('status', 1)
            ->latest('songs.created_at')
            ->paginate(10);

        return [
            'event' => $event,
            'songs' => $songs,
        ];
    }


    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.bannedSongs.list', $this->event),
            Button::make('Ban Selected Songs')
                ->method('chooseSong')
                ->confirm('This will also delete all song requests for the selected songs.')
                ->icon('plus')
        ];
    }

    public function layout(): iterable
    {
        return [
            ViewSongsLayoutNoEdit::class,
        ];
    }

    public function chooseSong(Request $request, Events $event)
    {
        $noPlaySongs = $request->get('selectedSongs');
        try {
            if (!empty($noPlaySongs)) {
                foreach ($noPlaySongs as $noPlaySongId) {
                    if (Song::find($noPlaySongId)->status == 0) continue;
                    NoPlaySong::create([
                        'song_id' => $noPlaySongId,
                        'event_id' => $event->id
                    ]);
                    SongRequest::where('song_id', $noPlaySongId)
                        ->where('event_id', $event->id)
                        ->delete();
                }
                Toast::success('Selected songs banned successfully');
            } else {
                Toast::warning('You have not selected any songs to ban');
            }
        } catch (Exception $e) {
            Toast::warning('An error occurred, try again later');
        }
    }
}
