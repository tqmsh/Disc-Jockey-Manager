<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use App\Models\Election;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
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
                ->route('platform.eventPromvote.list', $this->event->id)
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

            Election::create($electionField);

            Toast::success('Election Added Succesfully');
            
            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this election. Error Code: ' . $e->getMessage());
        }
    }
}
