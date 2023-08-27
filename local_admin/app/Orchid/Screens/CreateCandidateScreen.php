<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Student;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class CreateCandidateScreen extends Screen
{
    public $position;
    public $election;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        $election = Election::where('id',$position->election_id)->first();
        abort_if(Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $election->school_id, 403, 'You are not authorized to view this page.');
        return [
            'position' => $position,
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
        return 'Add New Candidate to position: ' .$this->position->position_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createCandidate',[$this->position]),
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
                // choose candidate that is in the event AND is a student
                Select::make('candidate.id')
                ->options(function(){
                    $arr= array();
                    $event_attendees = EventAttendees::where('event_id', $this->election->event_id) ->paginate(20);
                    foreach($event_attendees as $attendee){
                        $student = Student::where('user_id', $attendee->user_id)->first();
                        if ($this->election->event_id == $student->school_id){
                            $arr[$student -> id]= $student -> firstname .' '. $student -> lastname;
                        }
                    }
                    return $arr;
                })
                ->title('Candidate Name')
                ->required()
                ->empty('Choose a Candidate')
                ->horizontal(), 

                TextArea::make('candidate_bio')
                    ->title('Candidate Bio')
                    ->type('text')
                    ->placeholder('Something about yourself')
                    ->rows(5)
                    ->horizontal()

            ])->title('Make a Candidate'),
        ];
    }

    public function createCandidate(Request $request, Position $position){

        try{
            $student = Student::find($request->input('candidate.id'));
            $election = Election::where('id',$position->election_id)->first();
            $candidate = Candidate::where('user_id',$student->user_id)->first();
            if($candidate == null || $candidate->election_id != $election->id){
                $candidateField['candidate_bio'] = $request->input('candidate_bio');
                $candidateField['user_id'] = $student->user_id;
                $candidateField['candidate_name'] = $student->firstname.' '. $student->lastname;
                $candidateField['position_id'] = $position->id;
                $candidateField['election_id'] = $position->election_id;

                Candidate::create($candidateField);

                Toast::success('Candidate Added Succesfully');
                
                return redirect()->route('platform.eventPromvotePositionCandidate.list', $position->id);
            }

            else{
                Alert::error('This candidate already exists');
            }

        }catch(Exception $e){
            
            Alert::error('There was an error creating this candidate. Error Code: ' . $e->getMessage());
        }
    }
}
