<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use App\Orchid\Layouts\ViewCandidateLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewPositionLayout;
use App\Orchid\Screens\ViewElectionScreen as ScreensViewElectionScreen;
use Orchid\Screen\Actions\DropDown;

class ViewElectionScreen extends Screen
{
    public $event;
    public $election;
    public $position;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $election = Election::where('event_id', $event->id)->first();
        $position = Position::where('election_id', $election->id)->paginate(10);
        $candidate = Candidate::where('election_id',$election->id)->paginate(10);
        return [
            'event' => $event,
            'election' => $election,
            'position' => $position,
            'candidate' =>$candidate
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Prom Vote: ' . $this->election->election_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        // If election is ongoing
        if (now() < $this->election->end_date)
        {
            return [
                DropDown::make('Election Options')
                ->icon('options-vertical')
                ->list([
    
                    Link::make('Edit Election')
                        ->icon('pencil')
                        ->route('platform.eventPromvote.edit',$this->election->id),
    
                    Button::make('Delete Election')
                        ->icon('trash')
                        ->method('deleteElection',[$this->event])
                        ->confirm(__('Are you sure you want to DELETE this election? 🚨🚨🚨This action is PERMENANT and cannot be UNDONE🚨🚨🚨
                        In order to END an election, change the elections end date to any date in the past.')),
                ]),
    
                DropDown::make('Position Options')
                ->icon('options-vertical')
                ->list([
    
                    Link::make('Create Position')
                        ->icon('plus')
                        ->redirect() -> route('platform.eventPromvotePosition.create',$this->election->id),
    
                    Button::make('Delete Selected Positions')
                        ->icon('trash')
                        ->method('deletePosition')
                        ->confirm(__('Are you sure you want to delete selected positions?')),
                ]),
    
                DropDown::make('Candidate Options')
                ->icon('options-vertical')
                ->list([
    
                    Button::make('Delete Selected Candidates')
                        ->icon('trash')
                        ->method('deleteCandidates')
                        ->confirm(__('Are you sure you want to delete selected candidates?')),
                ]),
    
                Link::make('Back')
                    ->icon('arrow-left')
                    ->route('platform.event.list')
            ];
        }
        // if election is over
        else
        {
            return 
            [
                DropDown::make('Election Options')
                ->icon('options-vertical')
                ->list([
    
                    Link::make('Edit Election')
                        ->icon('pencil')
                        ->route('platform.eventPromvote.edit',$this->election->id),
    
                    Button::make('Delete Election')
                        ->icon('trash')
                        ->method('deleteElection',[$this->event])
                        ->confirm(__('Are you sure you want to DELETE this election? 🚨🚨🚨This action is PERMENANT and cannot be UNDONE🚨🚨🚨
                        In order to END an election, change the elections end date to any date in the past.')),
                ]),

                Link::make('Back')
                    ->icon('arrow-left')
                    ->route('platform.event.list')
            ];
        }
        
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        // If election is ongoing
        if (now() < $this->election->end_date)
            {
                return [
                    Layout::tabs([
                        "Positions"=>
                            ViewPositionLayout::class,
                        "All Candidates" =>
                            ViewCandidateLayout::class
                    ])
                ];
            }
        // If election is over
        else 
            {
                return[
                    Layout::view('election_status'),
                    Layout::tabs([
                        "All Candidates" =>
                            ViewCandidateLayout::class
                    ])
                ];
                
            }
    }

    public function deleteElection(Events $event)
    {   
        $election = Election::where('event_id', $event->id);
        $position = Position::where('election_id', $election->first()->id);
        try{
            foreach($position as $pos){
                $pos->delete();
            }
            $election->delete();

            Toast::success('Election deleted succesfully');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            Toast::error('There was a error trying to delete the selected events. Error Message: ' . $e);
        }
    }

    public function deletePosition(Request $request)
    {   
        //get all localadmins from post request
        $positions = $request->get('positions');
        
        try{
            //if the array is not empty
            if(!empty($positions)){

                foreach($positions as $position){
                    Position::where('id', $position)->delete();
                }

                Toast::success('Selected positions deleted succesfully');

            }else{
                Toast::warning('Please select positions in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected events. Error Message: ' . $e);
        }
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
            Toast::error('There was a error trying to deleted the selected events. Error Message: ' . $e);
        }
    }

    public function redirect($position, $type){
        $type = request('type');
        $position = Position::find(request('position'));
        if($type == 'edit'){
            return redirect() -> route('platform.eventPromvotePosition.edit', $position->id);
        }
        else if($type == 'candidate'){
            return redirect() -> route('platform.eventPromvotePositionCandidate.list', $position->id);
        }
        else {
            return redirect()->route('platform.event.list');
        }    
    }

    public function redirect_candidate($candidate){
        // TODOTODO Keep parameter or this thing below?
        $candidate = Candidate::find(request('candidate'));
        $election = Election::where('id', $candidate->election_id)->first();

        if (now() < $election->end_date)
        {
            return redirect() -> route('platform.eventPromvotePositionCandidate.edit', $candidate->id);
        }
        // If election is over
        else
        {
            Toast::error('Position and candidate edits cannot be made for an election that has passed its end date.');
        }
        
    }
}
