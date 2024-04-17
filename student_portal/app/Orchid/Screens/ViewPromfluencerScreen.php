<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

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
        return [];
    }
}
