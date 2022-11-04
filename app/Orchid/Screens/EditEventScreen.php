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
        $this->school = $this->event->getSchool($this->event->school);

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
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->event->school),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->school->value('country')),


                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->school->value('state_province')),


                Select::make('school_board')
                    ->title('School Board')
                    ->horizontal()
                    ->fromModel(School::class, 'school_board', 'school_board')
                    ->value($this->school->value('school_board')),
            ]),
        ];
    }

    public function update(Events $event, Request $request)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH

        $event->fill($request->all())->save();

        Toast::success('You have successfully updated ' . $request->input('event_name') . '.');

        return redirect()->route('platform.event.list');
    }

    public function delete(Events $event)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH
        
        $event->delete();

        Toast::success('You have successfully deleted the event.');

        return redirect()->route('platform.event.list');
    }
}
