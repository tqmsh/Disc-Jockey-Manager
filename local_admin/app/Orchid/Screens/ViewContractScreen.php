<?php

namespace App\Orchid\Screens;

use App\Models\Contract;
use App\Models\Localadmin;
use App\Models\School;
use App\Orchid\Layouts\ViewContractLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ViewContractScreen extends Screen
{
    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'contracts' => Contract::latest()
                ->where(
                    'state_province',
                    School::find(
                        Localadmin::where('user_id', Auth::id())->first()->school_id
                    )->state_province)
                ->filter(request(['title',]))->paginate(min(request()->query('pagesize', 10), 100)),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Contracts';
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
                ->route('platform.contract.list'),
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
            Layout::rows([
                Group::make([
                    Select::make('title')
                        ->title('Title')
                        ->empty('No Selection')
                        ->help('Type in boxes to search')
                        ->fromModel(Contract::where(
                            'state_province',
                            School::find(
                                Localadmin::where('user_id', Auth::id())->first()->school_id
                            )->state_province),
                        'title', 'title'),
                ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewContractLayout::class,
        ];
    }

    public function filter() {
        return redirect()->route('platform.contract.list', request(['title',]));
    }
}
