<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\ElectionVotes;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\ViewCandidateLayout;

class ViewVotingScreen extends Screen
{
    public $position;
    public $election;
    public $candidate;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        $candidate = Candidate::where('position_id', $position->id)->paginate(10);
        $election = Election::where('id', $position->election_id)->first();
        return [
            'position' => $position,
            'candidate' => $candidate,
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
        return 'Vote for the position: ' .$this->position->position_name;
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
                ->route('platform.election.list', $this->election->event_id)
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
            ViewCandidateLayout::class
        ];
    }

    public function voting($position){

        try{
            $voters = ElectionVotes::where('position_id', $position->id)->get();
            $user_id = 0; // using as a placeholder
            $voted = false;
            // check if they have voted yet through finding their user id through this position in election vote
            foreach($voters as $voter){
                if($voter->voter_user_id == $user_id){
                    Toast::warning('You have already voted for this position');
                    $voted = true;
                }
            }
            // if not, and add their information on election_votes database
            if(!$voted){

            }
        }catch(Exception $e){
                
            Alert::error('There was an error voting for this candidate. Error Code: ' . $e->getMessage());
        }

        
    }
}
