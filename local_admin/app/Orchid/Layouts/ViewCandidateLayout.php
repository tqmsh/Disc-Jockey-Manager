<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Candidate;
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
            // TD::make()
            //     ->render(function($candidate){
            //         return Button::make('Votes')->icon('people')->type(Color::DARK());
            //             // ->method('redirect',['position' =>$position->id, 'type'=> "candidate"]);
            // }), 
            TD::make('user_id', 'User ID')
                ->render(function (Candidate $candidate) {
                    return Link::make($candidate->user_id);
            }),
            TD::make('candidate_name', 'Candidate Name')
                ->render(function (Candidate $candidate) {
                    return Link::make($candidate->candidate_name);
            }),
            TD::make('candidate_bio', 'Candidate Bio')
                ->render(function (Candidate $candidate) {
                    return Link::make($candidate->candidate_bio);
            }),
            //Total Votes
            // TD::make('Candidate Bio')
            //     ->render(function ($candidate) {
            //         // $allVotes = totalVotes($candidate->id);
            //         return Link::make($candidate->candidate_bio);
            // }),
            TD::make()
                ->render(function($candidate){
                    return Button::make('Edit')->icon('pencil')->type(Color::PRIMARY())
                        ->method('redirect',['candidate' =>$candidate->id, 'type'=> "edit"]);
            }), 
        ];
    }
}
