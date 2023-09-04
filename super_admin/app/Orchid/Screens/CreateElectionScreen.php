<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Election;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class CreateElectionScreen extends Screen
{
    public $event;
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
        return 'Add New Election to ' . $this->event->event_name;
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
                ->method('createElection',[$this->event]),

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

                Input::make('election_name')
                    ->title('Election Name')
                    ->type('text')
                    ->required()
                    ->placeholder('New Election') 
                    ->horizontal(),

                DateTimer::make('start_date')
                    ->title('Election Start')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime(),

                DateTimer::make('end_date')
                    ->title('Election End')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime(),

            ])->title('Make an Election'),
        ];
    }

    public function createElection(Request $request, Events $event){

        try{

            $electionField = $request->all();
            $electionField['event_id'] = $event->id;
            $electionField['school_id'] = $event->school_id;

            if ($electionField['end_date'] < $electionField['start_date'])
            {
                Toast::error('END DATE must be after START DATE and START DATE must be before END DATE.');
                return redirect()->route('platform.event.list');
            }
            
            Election::create($electionField);

            Toast::success('Election Added Succesfully');
            
            return redirect()->route('platform.eventPromvote.list',$event->id);

        }catch(Exception $e){
            
            Alert::error('There was an error creating this election. Error Code: ' . $e->getMessage());
        }
    }
}
