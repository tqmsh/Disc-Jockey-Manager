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
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use App\Orchid\Layouts\ViewWinnersLayout;

use Orchid\Support\Facades\Toast;

class ViewWinnersScreen extends Screen
{
    public $election;
    public $event;

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

        // Election hasnt ended yet
        abort_if(now() < $election->end_date, 403, 'You are not authorized to view this page.');

        // putting winners in the database
        foreach ($positions as $position)
        {
            ViewWinnersScreen::winner_check($position);
        }

        // the candidates from this election who are winners 
        $candidate_ids = [];

        foreach($candidates as $candidate)
        {
            $candidate_ids[] = $candidate->id;
        }

        $winningCandidates = ElectionWinner::whereIn('candidate_id', $candidate_ids)->get();

        $election = Election::where('id',$election->id)->first();

        return [
            'election' => $election,
            'event' => $event,
            'positions' => $positions,
            'candidates' => $candidates,
            'winning_candidates' => $winningCandidates
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
                ->route('platform.election.list', $this->event)
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

    public function winner_check($position)
    {
        // $position = Position::find(request('position'));
        $candidates = Candidate::where('position_id', $position->id)->get();

        $highestVotes = 0;

        // Check for highest number of votes for the position
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
                    $field['position_id'] = $position->id;
                    ElectionWinner::firstOrCreate($field);

                    // IF NOT ALREADY THERE, ADD THEM IN
                } catch(Exception $e){
                
                Alert::error('There was an error updating a winner. Error Code: ' . $e->getMessage());
                }
            }
        }
        
    }
}
