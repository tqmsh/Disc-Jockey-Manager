<?php

namespace App\Orchid\Screens;

use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\ElectionVotes;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewCandidateLayout;
use Illuminate\Http\Request;
use Exception;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;


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
        $candidate = Candidate::where('position_id', $position->id)->paginate(min(request()->query('pagesize', 10), 100));
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

            Button::make('Delete Selected Candidates')
                ->icon('trash')
                ->method('deleteCandidates')
                ->confirm(__('Are you sure you want to delete selected candidates?')),
                
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

    public function deleteCandidates(Request $request)
    {   
        //get all localadmins from post request
        $candidates = $request->get('candidates');
        try{
            //if the array is not empty
            if(!empty($candidates)){

                foreach($candidates as $candidate){
                    Candidate::where('id', $candidate)->delete();
                }

                Toast::success('Selected candidates deleted succesfully');

            }else{
                Toast::warning('Please select candidates in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected events. Error Message: ' . $e->getMessage());
        }
    }

    public function redirect_candidate($candidate, $type){
        $type = request('type');
        $candidate = Candidate::find(request('candidate'));
        if($type == 'edit'){
            return redirect() -> route('platform.eventPromvotePositionCandidate.edit', $candidate->id);
        }
    }
}
