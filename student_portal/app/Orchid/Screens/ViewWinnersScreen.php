<?php

namespace App\Orchid\Screens;

use Exception;

use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\ElectionVotes;
use App\Models\ElectionWinner;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewWinnersLayout;

use Orchid\Support\Facades\Toast;

class ViewWinnersScreen extends Screen
{
    public $election;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Election $election) : iterable
    {
        $event = Events::where('id',$election->event_id)->first();
        $candidates = Candidate::where('election_id', $election->id)->get();
        $positions = Position::where('election_id', $election->id)->get();
        
        // TODO probably best to give user a warning instead
        $studentAttendee= EventAttendees::where('user_id', Auth::user()->id)->where('event_id', $event->id)->first();
        abort_if(!($studentAttendee->exists() &&  $studentAttendee-> ticketstatus == 'Paid'), 403);
        $election = Election::where('id',$election->id)->first();

        // TODO might not need all of these
        return [
            'election' => $election,
            'event' => $event,
            'positions' => $positions,
            'candidates' => $candidates,
        ];
    }  

    /**
     * The name of the screen is displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Election Winners";
    }

    /**
     * The screen's action buttons.
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
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout() : array
    {
        return [
            ViewWinnersLayout::class
        ];
    }

    public function totalVotes($candidate_id)
    {   
        $totalVotes = 0;
        $allVoters = ElectionVotes::where('candidate_id',$candidate_id)->get();

        if(!empty($allVoters)){

            foreach($allVoters as $voter){
                $totalVotes+=1;
            }

        }
        return $totalVotes;
    }

    public function winner_check()
    {
        $position = Position::find(request('position'));
        $candidates = Candidate::where('position_id', $position->id)->get();

        $highestVotes = 0;

        // Check for highest number of votes
        foreach($candidates as $candidate)
        {
            if (ViewWinnersScreen::totalVotes($candidate->id) > $highestVotes)
            {
                $highestVotes = ViewWinnersScreen::totalVotes($candidate->id);
            }
        }

        foreach($candidates as $candidate)
        {
            // Stores the winner(s) into database if not already there
            if (ViewWinnersScreen::totalVotes($candidate->id) == $highestVotes)
            {
                try {
                    // CHECK IF THIS WINNER WAS ALREADY PUT IN THE DATABASE
                    $field['candidate_id'] = $candidate->id;
                    ElectionWinner::firstOrCreate($field);

                    // IF NOT ALREADY THERE, ADD THEM IN
                } catch(Exception $e){
                
                Alert::error('There was an error updating a winner. Error Code: ' . $e->getMessage());
                }
            }
        }

        // AFTERWARDS REDIRECT TO CORRECT PAGE
        // REDIRECT TO VIEWPOSITIONWINNERSCREEN OR SOMETHING
        // THEN DISPLAY A CUSTOM PAGE FOR THE WINNER ON A BUTTON CLICK
        
    }

    // TODO MODIFY THIS AND PUT IN ABOVE FUNCTION
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
