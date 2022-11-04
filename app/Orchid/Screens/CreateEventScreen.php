<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\School;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class CreateEventScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add a New Event';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createEvent'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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

                Input::make('event_name')
                    ->title('Event Name')
                    ->type('text')
                    ->required()
                    ->placeholder('Colonel By\'s Main Event')
                    ->horizontal(),

                DateTimer::make('event_start_time')
                    ->title('Event Start')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime(),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime(),
                    
                Input::make('event_address')
                    ->title('Event Address')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 2381 Ogilvie Rd')
                    ->horizontal(),
                    
                Input::make('event_zip_postal')
                    ->title('Event Zip/Postal')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. K1J 7N4')
                    ->horizontal(),

                Input::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. Formal Attire')
                    ->horizontal(),

                Input::make('event_rules')
                    ->title('Event Rules')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. No Violence')
                    ->horizontal(),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->horizontal()
                    ->empty('No Selection')
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->empty('No Selection')
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),


                Select::make('state_province')
                    ->title('State/Province')
                    ->empty('No Selection')
                    ->horizontal()
                    ->fromModel(School::class, 'state_province', 'state_province'),


                Select::make('school_board')
                    ->title('School Board')
                    ->empty('No Selection')
                    ->horizontal()
                    ->fromModel(School::class, 'school_board', 'school_board'),
            ]),
        ];
    }

    public function createEvent(Request $request){

        //!PUT ALL THIS IN A TRY CATCH
        $formFields = $request->all();
        $formFields['event_creator'] = auth()->id();
        
        Events::create($formFields);

        Toast::success('Event Added Succesfully');
        
        return redirect()->route('platform.event.list');
    }
}
