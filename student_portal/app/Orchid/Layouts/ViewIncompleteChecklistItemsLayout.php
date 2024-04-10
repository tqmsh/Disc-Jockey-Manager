<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\ChecklistItem;
use App\Models\ChecklistUser;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewIncompleteChecklistItemsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'incomplete_checklist_items';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->align(TD::ALIGN_LEFT)
                ->render(function(ChecklistItem $checklist_item) {
                    return Button::make('Complete')
                        ->method('completeChecklistItem', ['checklist_item_id' => $checklist_item->id])
                        ->icon('check')
                        ->type(Color::SUCCESS());
                }),
            
            TD::make('title', 'Title'),
            
            TD::make('description', 'Description')
        ];
    }
}
