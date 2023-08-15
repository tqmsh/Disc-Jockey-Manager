<?php

namespace App\Orchid\Screens;

use App\Models\NoPlaySong;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Song;

class EditSongScreen extends Screen
{
    public string $name = 'Create/Edit Song';
    public ?string $description = '';
    public ?Song $song;

    public function query(Song $song): array
    {
        if ($song->exists) {
            $this->name = 'Edit Song: ' . $song->title;
            $this->description = 'Edit an existing song';
        } else {
            $this->name = 'Create Song';
            $this->description = 'Create a new song';
        }

        return [
            'song' => $song,
        ];
    }

    public function commandBar(): array
    {
        $buttons = [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.songs.list'),
            Button::make('Save')
                ->icon('check')
                ->method('save'),
        ];
        if ($this->song->exists) {
            $buttons[] = Button::make('Delete')->method('delete')
                ->confirm('Are you sure you want to delete this song?')
                ->parameters(['id' => $this->song->id])
                ->icon('trash');
        }
        return $buttons;
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('song.title')
                    ->title('Title')
                    ->placeholder('Song Title'),

                Input::make('song.artists')
                    ->title('Artists')
                    ->placeholder('Enter artists (separated by commas)'),

                CheckBox::make('song.explicit')
                    ->title('Explicit')
                    ->help('If the song\'s status is not `Approved`, the explicit field will be `Unknown`.')
                    ->sendTrueOrFalse(),

                CheckBox::make('song.status')
                    ->title('Status')
                    ->help("Check this box if the song should be added to the universal songs table. Note that changing a song's status from `Approved` to `Pending` will also unban said song from all events.")
                    ->sendTrueOrFalse()
            ])
        ];
    }

    public function save(Song $song, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'song.title' => 'required|max:255',
            'song.artists' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->get('song');
        if (Song::where("title", $data['title'])
            ->where("artists", $data['artists'])
            ->where("id", "!=", $song->id)
            ->exists()) {
            Toast::error('This song already exists in the database');
            return back()->withInput();
        }

        try {
            $song->fill($data)->save();

            if (!isset($data['status']) || !$data['status']) {
                NoPlaySong::where('song_id', $song->id)->delete();
            }

            Toast::success('Song saved successfully');
        } catch (Exception $e) {
            Toast::error('There was an error trying to save the song. Error Message: ' . $e->getMessage());
        }

        return redirect()->route('platform.songs.list');
    }

    public function delete(Song $song)
    {
        $song->delete();
        Toast::success('You have successfully deleted the song.');
        return redirect()->route('platform.songs.list');
    }
}
