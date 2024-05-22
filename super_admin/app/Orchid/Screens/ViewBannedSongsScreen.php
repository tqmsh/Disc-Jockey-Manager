<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\NoPlaySong;
use App\Models\Song;
use App\Orchid\Layouts\ViewSongsLayoutNoEdit;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class ViewBannedSongsScreen extends Screen
{
    public Events $event;

    public function name(): string
    {
        return 'Banned Songs: ' . $this->event->event_name;
    }

    public string $description = "View, ban, and unban songs for this event.";

    public function query(Request $request, Events $event): iterable
    {
        $filters = $request->get('filter');
        $songIds = NoPlaySong::where('event_id', $event->id)->pluck('song_id');
        $songs = Song::filter($filters)->whereIn('id', $songIds)->paginate(request()->query('pagesize', 10));

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
                ->route('platform.songreq.list', $this->event),
            Link::make('Ban A Song')
                ->icon('plus')
                ->route('platform.bannedSongs.create', $this->event),
            Button::make('Unban Selected Songs')
                ->icon('trash')
                ->confirm('Are you sure you want to unban the selected songs?')
                ->method('deleteSong'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ViewSongsLayoutNoEdit::class
        ];
    }


    public function deleteSong(Request $request, Events $event)
    {
        $noPlaySongs = $request->get('selectedSongs');
        try {
            if (!empty($noPlaySongs)) {
                foreach ($noPlaySongs as $noPlaySong) {
                    NoPlaySong::where('song_id', $noPlaySong)
                        ->where('event_id', $event->id)
                        ->delete();
                }
                Toast::success('Unbanned selected songs');
            } else {
                Toast::warning('You have not selected any songs');
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to unban the selected songs. Error Message: ' . $e->getMessage());
        }
    }
}
