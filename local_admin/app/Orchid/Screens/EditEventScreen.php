<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use Orchid\Screen\Screen;
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
        
        abort_if($this->event->school_id != Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'), 403);

        
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
            ]),
        ];
    }

    public function update(Events $event, Request $request)
    {
        try{

            $eventsFields = $request->all();

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
