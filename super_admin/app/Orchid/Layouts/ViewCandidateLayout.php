<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Candidate;
use App\Models\Position;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewCandidateLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'candidate';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (Candidate $candidate){
                    return CheckBox::make('candidates[]')
                        ->value($candidate->id)
                        ->checked(false);
            }),
            
            TD::make('position', 'Position')
                ->render(function (Candidate $candidate) {
                    return e(Position::find($candidate->position_id)->position_name);
            }),
            TD::make('candidate_name', 'Candidate Name')
                ->render(function (Candidate $candidate) {
                    return e($candidate->candidate_name);
            }),
            TD::make('candidate_bio', 'Candidate Bio')
                ->render(function (Candidate $candidate) {
                    return e($candidate->candidate_bio);
            }),
            TD::make('candidate_votes', 'Candidate Votes')
                ->render(function (Candidate $candidate) {
                    $totalVotes = $candidate->totalVotes($candidate->id);
                    return e($totalVotes);
            }),
            TD::make()
                ->render(function($candidate){
                    return Button::make('Edit')->icon('pencil')->type(Color::PRIMARY())
                        ->method('redirect_candidate',['candidate' =>$candidate->id]);
            }), 
        ];
    }
}
