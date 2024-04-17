<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class EditPromfluencerScreen extends Screen
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
        return 'Edit Promfluence';
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
            Button::make('Submit')
                ->icon('check')
                ->method('updatePromfluencer'),
            Button::make('Delete Promfluence')
                ->icon('trash')
                ->method('deletePromfluencer')
                ->confirm('Are you sure you would like to delete your Promfluence?'),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.promfluencer.view'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
