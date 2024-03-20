<?php

namespace App\Orchid\Screens;

use App\Models\Couple;
use App\Models\Events;
use App\Models\Localadmin;
use App\Orchid\Layouts\ViewCouplesLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ViewCouplesScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'couples' => Couple::filter(request(['event_id']))->latest('couples.created_at')
                ->paginate(20)
        ];
    }


    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Couples';
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
                ->route('platform.student.list')
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
                    Select::make('event_id')
                        ->title('Event:')
                        ->empty('No selection')
                        ->fromQuery(Events::query(), 'event_name'),
                ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewCouplesLayout::class
        ];
    }

    public function filter(){
        return redirect()->route('platform.couples.list', request(['event_id']));
    }

    public function redirect($event_id){
        return redirect()->route('platform.event.edit', $event_id);
    }
    public function redirect_2($couple_id){
        return redirect()->route('platform.couples.info', $couple_id);
    }
}
