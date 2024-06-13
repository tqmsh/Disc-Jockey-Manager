<?php

namespace App\Orchid\Layouts;

use App\Models\Candidate;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;use Orchid\Screen\TD;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentPollVote;

use App\Models\PollOption;

class VotePollLayoutVoted extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'options';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(function (PollOption $option) {
                    return e($option->title);
            }),

            TD::make()
                ->render(function($option){
                    $user = Auth::user();
                    $vote = StudentPollVote::where('user_id', $user->id)->where('poll_id', $option->poll_id)->where('poll_options_id', $option->id)->first();
                    // dd($vote);
                    if($vote){
                        return Button::make('Voted')->icon('check')->type(Color::SUCCESS())
                        ->confirm(__('You have already voted. Clicking confirm will change your vote to ' . $option->title))
                        ->method('change_vote',['option' =>$option->id]);
                    } else {
                        return Button::make('Change Vote')->icon('pencil')->type(Color::DARK())
                        ->confirm(__('You have already voted. Clicking confirm will change your vote to ' . $option->title))
                        ->method('change_vote',['option' =>$option->id]);
                    
                    }
                    
                    
            }), 
        ];
    }
}
