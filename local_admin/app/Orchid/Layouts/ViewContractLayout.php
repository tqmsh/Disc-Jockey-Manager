<?php

namespace App\Orchid\Layouts;

use App\Models\Contract;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewContractLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contracts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title'),
            TD::make('url', 'URL')
                ->render(function (Contract $contract) {
                    return Link::make($contract->url)
                        ->href($contract->url);
                }),
            TD::make('state_province', 'State/Province'),
            TD::make('description', 'Description'),
        ];
    }
}
