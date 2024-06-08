<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\ChecklistItem;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewChecklistItemsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'checklist_items';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (ChecklistItem $checklist_item){
                    return CheckBox::make('checklist_items[]')
                        ->value($checklist_item->id)
                        ->checked(false);
                }),
            
            TD::make('title', 'Title')
                ->render(function(ChecklistItem $checklist_item) {
                    return Link::make($checklist_item->title)
                        ->route('platform.checklist-items.edit', ['checklist' => $checklist_item->checklist, 'checklist_item' => $checklist_item]);
                }),
            
            TD::make('description', 'Description')
                ->render(function(ChecklistItem $checklist_item) {
                    return Link::make($checklist_item->description)
                        ->route('platform.checklist-items.edit', ['checklist' => $checklist_item->checklist, 'checklist_item' => $checklist_item]);
                }),

            TD::make()
                ->render(function(ChecklistItem $checklist_item) {
                    return Button::make('Edit')
                            ->type(Color::PRIMARY())
                            ->method('redirect', ['checklist_item' => $checklist_item->id]) 
                            ->icon('pencil');
                })
        ];
    }
}
