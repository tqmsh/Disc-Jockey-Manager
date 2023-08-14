<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Position;
use App\Models\Candidate;
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
    protected $target = 'positions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Position ID')
                ->render(function (Position $position) {
                    return Link::make($position->id);
            }),
            TD::make('position_name', 'Position Name')
                ->render(function (Position $position) {
                    return Link::make($position->position_name);
            }),
            TD::make()
                ->render(function($position){
                    return Button::make('View Winner')->icon('chess-king')->type(Color::DARK())
                        ->method('winner_check',['position'=>$position->id]);
            }), 
        ];
    }
}
