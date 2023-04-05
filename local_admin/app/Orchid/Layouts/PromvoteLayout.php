<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class PromvoteLayout extends Table
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
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [];
    }
}
