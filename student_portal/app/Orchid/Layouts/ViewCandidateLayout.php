<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\User;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;

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
            TD::make('candidate_video_url', 'Candidate Video')
                ->render(function (Candidate $candidate) {
                    $first_name = User::find($candidate->user_id)->firstname;
                    $hasSubmittedVideo = !is_null($candidate->candidate_video_url);

                    return Link::make($hasSubmittedVideo ? "{$first_name}'s Video" : 'No Video Submitted')
                            ->href($candidate->candidate_video_url ?? url()->full())
                            ->target($hasSubmittedVideo ? '_blank' : '');
            }),
            TD::make()
                ->render(function($candidate){
                    return Button::make('Vote')->icon('people')->type(Color::DARK())
                        ->confirm(__('Are you sure you want to vote for this candidate?'))
                        ->method('voting',['position' =>$candidate->position_id, 'candidate'=> $candidate->id]);
            }), 
        ];
    }
}
