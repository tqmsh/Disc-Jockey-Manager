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
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;
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
            ViewCandidateLayout::class,
            ViewElectionDatesLayout::class,
        ];
    }

    public function voting($position, $candidate){
        $position = request('position');
        $candidate = request('candidate');
        $positionFunction = Position::where('id',$position)->first();
        $election = Election::where('id',$positionFunction->election_id)->first();
        $now = now();
        try{
            $voters = ElectionVotes::where('position_id', $position)->get();
            $user_id = Auth::user()->id;
            $voted = false;
            foreach($voters as $voter){
                if($now > $election->end_date){
                    Toast::warning('You have passed the election date');
                    $voted = true;
                    return;
                }

                else if($voter->voter_user_id == $user_id){
                    $targetCandidate = Candidate::where('id', $candidate)->first();
                    
                    Alert::view('confirm_vote', Color::WARNING(), [
                        'name' => $targetCandidate->candidate_name
                    ]);
                    
                    $voted = true;
                    return;
                }
            }
            if(!$voted){
                $field['election_id'] = $election->id;
                $field['position_id'] = $position;
                $field['candidate_id'] = $candidate;
                $field['voter_user_id'] = $user_id;
                ElectionVotes::create($field);
                Toast::success('Voted Successfully');
            }
        }catch(Exception $e){
                
            Alert::error('There was an error voting for this candidate. Error Code: ' . $e->getMessage());
        }
        
    }
}
