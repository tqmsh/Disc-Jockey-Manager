<?php

namespace App\Orchid\Layouts;

use App\Models\Song;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;

class UserSongRequestsLayout extends Table
{
    protected $target = 'userSongRequests';
    public $title = 'Your Requested Songs';

    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (Song $song) {
                    return CheckBox::make('selectedUserSongRequests[]')
                        ->value($song->id)
                        ->checked(false);
                }),

            TD::make('title', 'Title')
                ->render(function (Song $song) {
                    return $song->title;
                }),

            TD::make('artists', 'Artist(s)')
                ->render(function (Song $song) {
                    return $song->artists;
                }),

            TD::make('explicit', 'Explicit')
                ->render(function (Song $song) {
                    return $song->explicit ? 'Yes' : 'No';
                }),
        ];
    }
}
