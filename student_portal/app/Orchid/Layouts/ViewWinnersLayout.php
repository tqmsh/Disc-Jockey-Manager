<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\ElectionWinner;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class ViewWinnersLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'winning_candidates';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('position', 'Position')
                ->render(function (ElectionWinner $winner) {
                    return e(Position::find($winner->position_id)->position_name);
            }),
            TD::make('candidate', 'Candidate')
                ->render(function (ElectionWinner $winner) {
                    return e(Candidate::find($winner->candidate_id)->candidate_name);
            }),
        ];
    }
}
