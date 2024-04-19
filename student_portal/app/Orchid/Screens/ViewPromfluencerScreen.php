<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

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
                ->canSee($this->promfluencer !== NULL)
                ->confirm('Are you sure you would like to delete your Promfluence?'),
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
        ] : [];
    }

    public function createPromfluencer()
    {
        if (Promfluencer::firstWhere('user_id', Auth::id()) !== NULL) {
            Toast::error('Promfluence already exists');
            return;
        }
        Promfluencer::create(['user_id' => Auth::id(),]);
        Toast::success('Promfluence created successfully');
    }

    public function deletePromfluencer()
    {
        $promfluencer = Promfluencer::firstWhere('user_id', Auth::id());
        if ($promfluencer === NULL) {
            Toast::error('Promfluence does not exist');
            return;
        }
        $promfluencer->delete();
        Toast::success('Promfluence deleted successfully');
    }
}
