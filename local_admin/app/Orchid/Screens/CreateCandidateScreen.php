<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Student;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Support\Facades\Auth;

class CreateCandidateScreen extends Screen
{
    public $position;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        return [
            'position' => $position
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

                // unclear for this
                Select::make('candidate.id')
                ->options(function(){
                    $arr= array();
                    $students = Student::where('school_id', Localadmin::where('user_id', Auth::user()->id)->pluck('school_id'))->paginate(20);
                    foreach($students as $student){
                        // if(!NoPlaySong::where('song_id', $song -> id)->where('event_id', $this -> event -> id)->exists()){
                            $arr[$student -> id]= $student -> firstname .' '. $student -> lastname;
                        // }
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
            $candidate = Candidate::where('user_id',$student->user_id)->first();
            if($candidate == null){
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
