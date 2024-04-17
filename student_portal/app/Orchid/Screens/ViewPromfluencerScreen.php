<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ViewPromfluencerScreen extends Screen
{
    public $promfluencer;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'promfluencer' => Promfluencer::firstWhere('user_id', Auth::id()),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Promfluence';
    }

    /**
     * Display description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Instructional/Disclaimer Text Placeholder';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Promfluence')
                ->icon('plus')
                ->method('createPromfluencer')
                ->canSee($this->promfluencer === NULL),
            Link::make('Edit Promfluence')
                ->icon('pencil')
                ->route('platform.promfluencer.edit')
                ->canSee($this->promfluencer !== NULL),
            Button::make('Delete Promfluence')
                ->icon('trash')
                ->method('deletePromfluencer')
                ->canSee($this->promfluencer !== NULL),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return $this->promfluencer !== null ? [
            Layout::legend($this->promfluencer, [
                Sight::make('name', 'Name')
                    ->render(function () {
                        return ($this->promfluencer->user->firstname . ' ' . $this->promfluencer->user->lastname) ?? '';
                    }),
                Sight::make('email', 'Email')
                    ->render(fn () => $this->promfluencer->user->email ?? ''),
                Sight::make('phonenumber', 'Phone Number')
                    ->render(fn () => $this->promfluencer->user->phonenumber ?? ''),
                Sight::make('school', 'School')
                    ->render(fn () => $this->promfluencer->user->student->school ?? ''),
                Sight::make('grade', 'Grade')
                    ->render(fn () => $this->promfluencer->user->student->grade ?? ''),
                Sight::make('instagram', 'Instagram')
                    ->render(fn () => $this->promfluencer->instagram ?? ''),
                Sight::make('tiktok', 'TikTok')
                    ->render(fn () => $this->promfluencer->tiktok ?? ''),
                Sight::make('snapchat', 'Snapchat')
                    ->render(fn () => $this->promfluencer->snapchat ?? ''),
                Sight::make('YouTube', 'YouTube')
                    ->render(fn () => $this->promfluencer->youtube ?? ''),
            ]),
        ] : [];
    }
}
