<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class EditElectionScreen extends Screen
{
    public $election;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Election $election): iterable
    {
        return [
            'election' => $election
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Election: ' .$this->election->election_name;
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

            Button::make('Delete Election')
                ->icon('trash')
                ->method('endElection'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.eventPromvote.list', $this->election->event_id)
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
                    ->horizontal()
                    ->value($this->election->election_name),

                DateTimer::make('start_date')
                    ->title('Election Start')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime()
                    ->value($this->election->start_date),

                DateTimer::make('end_date')
                    ->title('Election End')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime()
                    ->value($this->election->end_date),

            ])->title('Edit an Election'),
        ];
    }

    public function update(Election $election, Request $request)
    {
        try{

            $electionFields = $request->all();

            $election->update($electionFields);

            Toast::success('You have successfully updated ' . $request->input('election_name') . '.');

            return redirect()->route('platform.eventPromvote.list',$election->event_id);

        }catch(Exception $e){

            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }

    public function endElection(Events $event)
    {   
        $election = Election::where('event_id', $event->id);
        $position = Position::where('election_id', $election->first()->id);
        try{
            foreach($position as $pos){
                $pos->delete();
            }
            $election->delete();

            Toast::success('Election ended succesfully');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected events. Error Message: ' . $e);
        }
    }
}
