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
            TD::make('checkboxes')
                ->render(function (Contract $contract) {
                    return CheckBox::make('contracts[]')
                        ->value($contract->id)
                        ->checked(false);
                }),
            TD::make('title', 'Title')
                ->render(function (Contract $contract) {
                    return Link::make($contract->title)
                        ->route('platform.contract.edit', $contract);
                }),
            TD::make('url', 'URL')
                ->render(function (Contract $contract) {
                    return Link::make($contract->url)
                        ->href($contract->url);
                }),
            TD::make('state_province', 'State/Province')
                ->render(function (Contract $contract) {
                    return Link::make($contract->state_province)
                        ->route('platform.contract.edit', $contract);
                }),
            TD::make('description', 'Description')
                ->render(function (Contract $contract) {
                    return Link::make($contract->description)
                        ->route('platform.contract.edit', $contract);
                }),
            TD::make()
                ->render(function (Contract $contract) {
                    return Button::make('Edit')->type(Color::PRIMARY())->method('redirect', ['contract' => $contract->id])->icon('pencil');
                }),
        ];
    }
}
