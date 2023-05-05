<?php

namespace App\Orchid\Screens;

use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\ElectionVotes;
use App\Orchid\Layouts\ViewCandidateLayout;

class ViewCandidateScreen extends Screen
{
    public $position;
    public $candidate;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        $candidate = Candidate::where('position_id', $position->id)->paginate(10);
        return [
            'position' => $position,
            'candidate' => $candidate
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
        return [];
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
