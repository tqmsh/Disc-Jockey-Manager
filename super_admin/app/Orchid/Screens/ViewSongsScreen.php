<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewSongsLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use App\Models\Song;

class ViewSongsScreen extends Screen
{
    public string $name = 'Songs';
    public ?string $description = 'View, create, and delete songs';

    public function query(Request $request): iterable
    {
        $filters = $request->get('filter');
        return [
            'songs' => Song::filter($filters)
                ->latest('songs.created_at')
                ->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Song')
                ->icon('plus')
                ->route('platform.songs.edit'),

            Button::make('Approve Selected Songs')
                ->icon('check')
                ->method('approve'),

            Button::make('Delete Selected Songs')
                ->icon('trash')
                ->confirm('Are you sure you want to delete the selected songs?')
                ->method('delete'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ViewSongsLayout::class,
        ];
    }

    //this method will mass approve selected songs
    public function approve(Request $request)
    {
        $songs = $request->get('selectedSongs');
        try {
            if (!empty($songs)) {
                Song::whereIn('id', $songs)->update(['status' => 1]);
                Toast::success('Selected songs approved successfully');
            } else {
                Toast::warning('Please select the songs to approve');
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to approve the selected songs. Error Message: ' . $e->getMessage());
        }
    }


    public function delete(Request $request)
    {
        $songs = $request->get('selectedSongs');
        try {
            if (!empty($songs)) {
                Song::whereIn('id', $songs)->delete();
                Toast::success('Selected songs deleted successfully');
            } else {
                Toast::warning('Please select the songs to delete');
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to delete the selected songs. Error Message: ' . $e->getMessage());
        }
    }
}
