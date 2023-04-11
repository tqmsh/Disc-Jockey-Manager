<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Color;

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
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime(),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime(),


                Input::make('event_address')
                ->title('Event Address')
                ->type('text')
                ->horizontal()
                ->value($this->event->event_address),
                
                Input::make('event_zip_postal')
                ->title('Event Zip/Postal')
                ->type('text')
                ->horizontal()
                ->value($this->event->event_zip_postal),



                Input::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->placeholder('Ex. Formal Attire')
                    ->horizontal(),

                Input::make('event_rules')
                    ->title('Event Rules')
                    ->type('text')
                    ->placeholder('Ex. No Violence')
                    ->horizontal(),

                Select::make('venue_id')
                    ->title('Venue')
                    ->fromQuery(Vendors::query()->where('category_id', Categories::where('name', 'LIKE', '%'. 'Venue' . '%')->first()->id), 'company_name')
                    ->empty('Start typing to Search...')
                    ->horizontal(),
            ])->title('Make your dream event'),
        ];
    }

    public function createEvent(Request $request){

        $school = School::where('id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->first();

        try{

            $formFields = $request->all();
            $formFields['event_creator'] = auth()->id();
            $formFields['school_id'] = $school->id;
            $formFields['school'] = $school->school_name;
            $formFields['region_id'] = $school->region_id;

            Events::create($formFields);

            Toast::success('Event Added Succesfully');
            
            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
        }
    }
}
