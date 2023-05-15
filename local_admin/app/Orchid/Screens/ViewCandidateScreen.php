<?php

namespace App\Orchid\Screens;

use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\ElectionVotes;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewCandidateLayout;

class ViewCandidateScreen extends Screen
{
    public $position;
    public $candidate;
    public $election;
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
        return 'Candidates of ' .$this->position->position_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add Candidate')
                ->icon('plus')
                ->redirect() -> route('platform.eventPromvotePositionCandidate.create',$this->position->id),
                
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
            ViewCandidateLayout::class
        ];
    }

    public function redirect($candidate, $type){
        $type = request('type');
        $candidate = Candidate::find(request('candidate'));
        if($type == 'edit'){
            // dd($candidate);
            return redirect() -> route('platform.eventPromvotePositionCandidate.edit', $candidate->id);
        }
        else {
            return redirect()->route('platform.event.list');
        }    
    }

    public function totalVotes($candidate_id)
    {   
        $candidate_id = request('candidate_id');
        $totalVotes = 0;
        $allVoters = ElectionVotes::where('candidate_id',$candidate_id)->get();

        if(!empty($allVoters)){

            foreach($allVoters as $voter){
                $totalVotes+=1;
            }

        }

        return $totalVotes;

    }
}
