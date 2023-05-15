<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;

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

                Input::make('candidate_name')
                    ->title('Candidate Name')
                    ->type('text')
                    ->required()
                    ->placeholder('New Candidate') 
                    ->horizontal(),

                TextArea::make('candidate_bio')
                    ->title('Candidate Bio')
                    ->type('text')
                    ->required()
                    ->placeholder('Something about yourself')
                    ->rows(5)
                    ->horizontal()

            ])->title('Make a Position'),
        ];
    }

    public function createCandidate(Request $request, Position $position){

        try{

            $candidateField = $request->all();
            $candidateField['position_id'] = $position->id;
            $candidateField['election_id'] = $position->election_id;

            Candidate::create($candidateField);

            Toast::success('Position Added Succesfully');
            
            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this candidate. Error Code: ' . $e->getMessage());
        }
    }
}
