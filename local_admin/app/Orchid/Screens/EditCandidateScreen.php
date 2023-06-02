<?php

namespace App\Orchid\Screens;

use App\Models\Election;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\Student;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\EventAttendees;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;

class EditCandidateScreen extends Screen
{
    public $candidate;
    public $position;
    public $positions;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Candidate $candidate): iterable
    {
        $position = Position::where('id',$candidate->position_id)->first();
        $positions = Position::where('election_id',$candidate->election_id)->paginate(20);
        // $election = Election::where('id', $candidate->election_id)->first();
        // abort_if(Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $election->school_id, 403, 'You are not authorized to view this page.');
        return [
            'candidate' => $candidate,
            'position' => $position,
            'positions' => $positions
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Candidate: ' .$this->candidate->candidate_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.eventPromvotePositionCandidate.list', $this->position->id)
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
            Layout::rows([
                TextArea::make('candidate_bio')
                    ->title('Candidate Bio')
                    ->type('text')
                    // ->placeholder('Something about yourself')
                    ->rows(5)
                    ->horizontal()
                    ->value($this->candidate->candidate_bio),

                    Select::make('position_id')
                    ->empty($this->position->position_name,$this->position->id)
                    ->options(function(){
                        $arr= array();
                        foreach($this->positions as $thisPosition){
                                $arr[$thisPosition -> id]= $thisPosition->position_name;
                        }
                        return $arr;
                    })
                    ->title('Position')
                    ->required()
                    ->horizontal(), 

            ])->title('Make a Candidate'),
        ];
    }

    public function update(Candidate $candidate, Request $request)
    {
        try{

            $candidateFields = $request->all();

            $candidate->update($candidateFields);

            Toast::success('You have successfully updated this candidate');

            return redirect()->route('platform.eventPromvotePositionCandidate.list', $candidate->position_id);

        }catch(Exception $e){

            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }
}
