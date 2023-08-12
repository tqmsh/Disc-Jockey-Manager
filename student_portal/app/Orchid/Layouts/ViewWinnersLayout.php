<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Position;
use App\Models\Candidate;
use Orchid\Support\Color;
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
                ->render(function (Events $event) {
                    return Button::make('Election')
                        ->icon('people')         
                        ->method('redirect', ['event_id' => $event->id, 'type' => 'election'])
                        ->type(Color::LIGHT());
                }),
        ];
    }
}
