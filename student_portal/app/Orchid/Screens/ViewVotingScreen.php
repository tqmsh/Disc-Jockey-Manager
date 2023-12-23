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
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewCandidateLayout;
use App\Orchid\Layouts\ViewCandidateLayoutVoted;
use Orchid\Support\Facades\Layout;

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

        // Election hasnt started yet
        abort_if(now() < $election->start_date, 403, 'You are not authorized to view this page.');

        return [
            'position' => $position,
            'candidate' => $candidate,
            'election' => $election,
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

        // IF ELECTION ENDED
        if ((ViewVotingScreen::isElectionPassed($this->position)) == true)
        {
            return [
                Layout::modal('This election has ended. You will no longer be able to vote.', [
                    Layout::rows([]),
                ])->withoutApplyButton()->open(),
                ViewCandidateLayout::class,
            ];
        }
        // IF USER HAS NOT VOTED
        else if ((ViewVotingScreen::hasVoted($this->position->id)) == false)
        {
            return [
                Layout::modal('You didnt vote for this position yet! You have until ' . $this->election->end_date, [
                    Layout::rows([]),
                ])->withoutApplyButton()->open(),
                ViewCandidateLayout::class,
            ];
        }
        // IF USER HAS VOTED
        else
        {
            return [
                ViewCandidateLayoutVoted::class,
            ];
            
        }
    }

    public function hasVoted($position)
    {
        try{
            $voters = ElectionVotes::where('position_id', $position)->get();
            $user_id = Auth::user()->id;
            foreach($voters as $voter){
                if($voter->voter_user_id == $user_id){
                    return true;
                }
            }
            return false;
        }catch(Exception $e){
            Alert::error('There was an error checking the vote status. Error Code: ' . $e->getMessage());
        }  
    }

    public function isElectionPassed($position)
    {
        try{
            $election = Election::where('id',$position->election_id)->first();

            if(now() > $election->end_date){
                Toast::warning('You have passed the election date');
                return true;
            }
            
        } catch(Exception $e){
            Alert::error('There was an error checking the election date status. Error Code: ' . $e->getMessage());
        }    
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

            if($now > $election->end_date){
                Toast::warning('You have passed the election date');
                $voted = true;
                return;
            }
            foreach($voters as $voter){  
                if($voter->voter_user_id == $user_id){
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

    public function change_vote($position, $candidate){
        $position = request('position');
        $candidate = request('candidate');
        $positionFunction = Position::where('id',$position)->first();
        $election = Election::where('id',$positionFunction->election_id)->first();
        $election_vote = ElectionVotes::where([['election_id',$positionFunction->election_id],
                                                ['position_id', $position],
                                                ['voter_user_id', Auth::user()->id],])->first();

        $now = now();
        try{
            $user_id = Auth::user()->id;
            $voted = false;

            if($now > $election->end_date){
                Toast::warning('You have passed the election date');
                $voted = true;
                return;
            }
            
            // DELETING OLD VOTE
            try{
                $election_vote->delete();
            } catch(Exception $e){
                Alert::error('There was an error changing your vote. Error Code: ' . $e->getMessage());
            }   
            // MAKING NEW VOTE
            if(!$voted){
                $field['election_id'] = $election->id;
                $field['position_id'] = $position;
                $field['candidate_id'] = $candidate;
                $field['voter_user_id'] = $user_id;
                ElectionVotes::create($field);
                Toast::success('Changed Vote Successfully');
            }
        }catch(Exception $e){
                
            Alert::error('There was an error voting for this candidate. Error Code: ' . $e->getMessage());
        }
    }
}
