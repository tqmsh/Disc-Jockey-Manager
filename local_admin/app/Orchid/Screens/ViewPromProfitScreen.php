<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use App\Models\Events;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Support\Facades\Auth;

class ViewPromProfitScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'events' => Events::where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->latest('events.created_at')->paginate(request()->query('pagesize', 10)),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Prom Profit';
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
            Layout::table('events', [
                TD::make('event_name', 'Event Name')
                    ->render(function (Events $event) {
                        return Link::make($event->event_name)
                            ->route('platform.event.edit', $event);
                    }),
                TD::make()
                    ->render(function($event){
                        return Button::make('Budget')->method('redirect', ['event_id' => $event->id, 'type' => 'budget'])->icon('book-open')->type(Color::DARK());
                    }), 

                TD::make()
                    ->render(function($event){
                        return Button::make('Actual')->method('redirect', ['event_id' => $event->id, 'type' => 'actual'])->icon('wallet')->type(Color::DEFAULT());
                    }), 
            ])->title('Events'),
        ];
    }
    public function redirect($event, $type){
        switch($type){
            case 'budget':
                return redirect()->route('platform.budget.list', $event);
            case 'actual':
                return redirect()->route('platform.actual.list', $event);
        }
    }
}
