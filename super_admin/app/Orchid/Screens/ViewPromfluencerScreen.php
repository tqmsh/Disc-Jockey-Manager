<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use App\Models\School;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ViewPromfluencerScreen extends Screen
{
    public $promfluencers;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'promfluencers' => Promfluencer::filter(request(['school', 'grade',]))->paginate(10),
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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Select::make('school')
                        ->title('School')
                        ->empty('No Selection')
                        ->fromModel(School::class, 'school_name')
                        ->help('Type in boxes to search'),
                    Select::make('grade')
                        ->title('Grade')
                        ->empty('No Selection')
                        ->options([
                            9 => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                        ]),
                ]),
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),
            Layout::table('promfluencers', [
                TD::make('name')
                    ->render(function (Promfluencer $promfluencer) {
                        return $promfluencer->user->firstname . ' ' . $promfluencer->user->lastname;
                    }),
                TD::make('school')
                    ->render(fn (Promfluencer $promfluencer) => $promfluencer->user->student->school),
                TD::make('grade')
                    ->render(fn (Promfluencer $promfluencer) => $promfluencer->user->student->grade),
            ]),
        ];
    }

    public function filter()
    {
        return redirect()->route('platform.promfluencer.list', request(['school', 'grade',]));
    }
}
