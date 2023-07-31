<?php
namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

class ViewElectionDatesLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'elections';

    /**
     * @return TD[]
     */
    protected function columns() : iterable
    {
        return [
            TD::make('start_date', 'Start Date'),
            TD::make('created_at')->sort(),
        ];
    }
}