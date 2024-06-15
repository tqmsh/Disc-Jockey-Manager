<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Poll;
use App\Models\PollOption;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\VotePollLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentPollVote;
use App\Orchid\Layouts\VotePollLayoutVoted;
use Orchid\Support\Facades\Layout;


class VotePollScreen extends Screen
{
    public $poll;
    public $options;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Poll $poll): iterable
    {
        $options = PollOption::where('poll_id', $poll->id)->get();

        return [
            'poll' => $poll,
            'options' => $options,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Vote for the poll: ' .$this->poll->title;
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
                ->route('platform.polls.list')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        // IF USER HAS NOT VOTED
        if ((VotePollScreen::hasVoted($this->poll->id)) == false)
        {
            return [
                Layout::modal('You have not voted yet yet! You have until ' . $this->poll->end_date, [
                    Layout::rows([]),
                ])->withoutApplyButton()->open(),
                VotePollLayout::class,
            ];
        }
        // IF USER HAS VOTED
        else
        {
            return [
                VotePollLayoutVoted::class,
            ];
        }
    }

    public function voting($option){
        $option = request('option');

        // $positionFunction = Position::where('id',$position)->first();
        $optionFunction = PollOption::where('id',$option)->first();
        $poll = Poll::where('id',$optionFunction->poll_id)->first();
        
        $now = now();
        try{
            $voters = StudentPollVote::where('poll_id', $poll->id)->get();
            $user_id = Auth::user()->id;
            $voted = false;

            if($now > $poll->end_date){
                Toast::warning('You have passed the election date');
                $voted = true;
                return;
            }
            foreach($voters as $voter){  
                if($voter->user_id == $user_id){
                    $voted = true;
                    return;
                }
            }
            if(!$voted){
                $field['poll_id'] = $poll->id;
                $field['poll_options_id'] = $option;
                $field['user_id'] = $user_id;
                StudentPollVote::create($field);
                Toast::success('Voted Successfully');
            }
        }catch(Exception $e){
                
            Alert::error('There was an error voting for this poll. Error Code: ' . $e->getMessage());
        }
        
    }

    public function hasVoted($poll)
    {
        try{
            $voters = StudentPollVote::where('poll_id', $poll)->get();
            $user_id = Auth::user()->id;
            foreach($voters as $voter){
                if($voter->user_id == $user_id){
                    return true;
                }
            }
            return false;
        }catch(Exception $e){
            Alert::error('There was an error checking the vote status. Error Code: ' . $e->getMessage());
        }  
    }

    public function change_vote($option){
        $option = request('option');
        $optionFunction = PollOption::where('id',$option)->first();
        $poll = Poll::where('id',$optionFunction->poll_id)->first();
        $user_id = Auth::user()->id;

        // dd($poll);
        // dd($optionFunction->id);
        $vote = StudentPollVote::where('user_id', $user_id)->where('poll_id', $poll->id)->first();
        // dd($vote);
        
        $now = now();
        try{
            $user_id = Auth::user()->id;
            $voted = false;

            if($now > $poll->end_date){
                Toast::warning('You have passed the End date');
                $voted = true;
                return;
            }
            
            // DELETING OLD VOTE
            try{
                $vote->delete();
            } catch(Exception $e){
                Alert::error('There was an error changing your vote. Error Code: ' . $e->getMessage());
            }   
            // MAKING NEW VOTE
            if(!$voted){
                $field['poll_id'] = $poll->id;
                $field['poll_options_id'] = $option;
                $field['user_id'] = $user_id;
                StudentPollVote::create($field);
                Toast::success('Vote Changed Successfully');
            }
        }catch(Exception $e){
                
            Alert::error('There was an error changing your vote. Error Code: ' . $e->getMessage());
        }
    }
}
