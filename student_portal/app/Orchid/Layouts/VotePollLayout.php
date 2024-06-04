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

class VotePollLayout extends Table
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
                    return Button::make('Vote')->icon('people')->type(Color::DARK())
                        ->confirm(__('Are you sure you want to vote for this?'))
                        ->method('voting',['option' =>$option->id]);
            }), 
        ];
    }
}
