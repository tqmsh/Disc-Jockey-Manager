<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ViewPromfluencerDetailedScreen extends Screen
{
    public $promfluencer;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Promfluencer $promfluencer): iterable
    {
        return [
            'promfluencer' => $promfluencer
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Promfluencer: ' . $this->promfluencer->user->firstname . ' ' . $this->promfluencer->user->lastname;
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
                ->route('platform.promfluencer.list'),
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
            Layout::legend('promfluencer', [
                Sight::make('name', 'Name')
                    ->render(function () {
                        return ($this->promfluencer->user->firstname . ' ' . $this->promfluencer->user->lastname) ?? '';
                    }),
                Sight::make('user.email', 'Email'),
                Sight::make('user.phonenumber', 'Phone Number'),
                Sight::make('user.student.school', 'School'),
                Sight::make('user.student.grade', 'Grade'),
                Sight::make('instagram', 'Instagram'),
                Sight::make('tiktok', 'TikTok'),
                Sight::make('snapchat', 'Snapchat'),
                Sight::make('youtube', 'YouTube'),
            ]),
        ];
    }
}
