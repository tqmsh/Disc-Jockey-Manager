<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use App\Models\Region;
use App\Models\BeautyGroup;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use App\Models\Poll;
use App\Models\Localadmin;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentPollVote;

class ViewPollsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'polls';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            // TD::make()
            //     ->render(function (Poll $poll){
            //         return CheckBox::make('polls[]')
            //             ->value($poll->id)
            //             ->checked(false);
            // }),

            TD::make('title', 'Title')
                ->render(function (Poll $poll) {
                    return e($poll->title);
            }),
            TD::make('description', 'Description')
                ->render(function (Poll $poll) {
                    return e($poll->description);
            }),
            TD::make('end_date', 'End Date')
                ->render(function (Poll $poll) {
                    return e(date('F j, Y', strtotime($poll->end_date)));
            }),
            
            TD::make()
                ->render(function(Poll $poll){
                    $user = Auth::user();
                    $vote = StudentPollVote::where('user_id', $user->id)->where('poll_id', $poll->id)->first();
                    if($vote){
                        return Button::make('Change Vote')->icon('pencil')->type(Color::DARK())
                            ->method('redirect_poll',['poll' =>$poll->id]);
                    } else {
                        return Button::make('Vote')->icon('check')->type(Color::PRIMARY())
                            ->method('redirect_poll',['poll' =>$poll->id]);
                    
                    }
            }),
        ];
    }
}
