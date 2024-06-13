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

class ViewPastPollsLayout extends Table
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
                    return Button::make('Results')->icon('trophy')->type(Color::PRIMARY())
                        ->method('redirect_poll',['poll' =>$poll->id]);
                   
            }),
        ];
    }
}
