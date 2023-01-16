<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class EditEventScreen extends Screen
{
    public $event;
    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Event: ' . $this->event->event_name;
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
                ->method('update'),

            Button::make('Delete Event')
                ->icon('trash')
                ->method('delete'),

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
        $this->school = School::find($this->event->school_id);

        return [
            
            Layout::rows([

                Input::make('event_name')
                    ->title('Event Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_name),

                DateTimer::make('event_start_time')
                    ->title('Event Start')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_start_time),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_finish_time),
                    
                Input::make('event_address')
                    ->title('Event Address')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_address),
                    
                Input::make('event_zip_postal')
                    ->title('Event Zip/Postal')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_zip_postal),

                Input::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_info),

                Input::make('event_rules')
                    ->title('Event Rules')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_rules),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->school->school_name),

                Select::make('country')
                    ->title('Country')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->school->country),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->school->state_province),

                Select::make('county')
                    ->title('County')
                    ->empty('Start typing to Search...')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county')
                    ->value($this->school->county),
            ]),
        ];
    }

    public function update(Events $event, Request $request)
    {
        try{

            $school_id = School::where('school_name', $request->input('school'))
                                ->where('county', $request->input('county'))
                                ->where('state_province', $request->input('state_province'))
                                ->get('id')->value('id');

            if(is_null($school_id)){
                throw New Exception('You are trying to enter a invalid school');
            }

            $eventsFields = $request->all();
            $eventsFields['school_id'] = $school_id;

            $event->update($eventsFields);

            Toast::success('You have successfully updated ' . $request->input('event_name') . '.');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(Events $event)
    {
        try{

            $event->delete();

            Toast::success('You have successfully deleted the event.');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this event. Error Code: ' . $e);
        }     
    }
}
