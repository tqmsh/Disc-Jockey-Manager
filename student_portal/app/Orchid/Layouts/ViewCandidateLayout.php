<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

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
            TD::make()
                ->render(function($candidate){
                    return Button::make('Vote')->icon('people')->type(Color::DARK())
                        ->confirm(__('Are you sure you want to vote for this candidate?'));
                        // ->method('redirect',['candidate' =>$candidate->id, 'type'=> "edit"]);
            }), 
        ];
    }
}
