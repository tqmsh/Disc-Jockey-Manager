<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewPositionLayout;
use Orchid\Support\Facades\Layout;

class ViewElectionScreen extends Screen
{
    public $event;
    public $election;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $studentAttendee= EventAttendees::where('user_id', Auth::user()->id)->where('event_id', $event->id)->first();
        //! NEED TO ADD THIS WHEN PAYMENT INTEGRATION IS DONE && $studentAttendee->ticketstatus == 'Paid'
        abort_if(!($studentAttendee->exists() ), 403);
        $election = Election::where('event_id',$event->id)->first();
        
        
        return [
            'election' => $election,
            'position' => Position::where('election_id',$election->id)->paginate(10),
            'event' => $event,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Election: ' .$this->election->election_name;
    }


    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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
            Layout::view('election_status'),
            ViewPositionLayout::class,
        ];
    }

    public function redirect($position, $type){
        $type = request('type');
        $position = Position::find(request('position'));
        if($type == 'vote'){
            return redirect() -> route('platform.election.vote', $position->id);
        }
        else {
            return redirect()->route('platform.event.list');
        }    
    }
}
