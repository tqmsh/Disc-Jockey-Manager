<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\StudentPollVote;

class ViewPollResultsLayout extends Table
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

            TD::make('total_votes', 'Total Votes')
                ->render(function (PollOption $option) {
                    $votes = StudentPollVote::where('poll_options_id', $option->id)->count();

                    return e($votes);
            }),

            TD::make('percentage', '%')
                
            ->render(function (PollOption $option) {
                $votes = StudentPollVote::where('poll_options_id', $option->id)->count();

                $total_votes = StudentPollVote::where('poll_id', $option->poll_id)->count();

                $percentage = 0;
                if ($total_votes > 0) {
                    $votes = StudentPollVote::where('poll_options_id', $option->id)->count();
                    $percentage = ($votes / $total_votes) * 100;
                }
                return e($percentage . '%');

            }),
        ];
    }
}
