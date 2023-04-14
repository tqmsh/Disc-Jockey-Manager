<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewPositionLayout;

class ViewElectionScreen extends Screen
{
    public $event;
    public $election;
    public $position;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $election = Election::where('event_id', $event->id)->first();
        $position = Position::where('election_id', $election->id)->paginate(10);
        return [
            'event' => $event,
            'election' => $election,
            'position' => $position
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Prom Vote: ' . $this->election->election_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create Position')
                ->icon('plus')
                ->redirect() -> route('platform.eventPromvotePosition.create',$this->election->id),
            
            Button::make('End Election')
                ->icon('trash')
                ->method('endElection',[$this->event,$this->position])
                ->confirm(__('Are you sure you want to end election?')),

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
            ViewPositionLayout::class
        ];
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
