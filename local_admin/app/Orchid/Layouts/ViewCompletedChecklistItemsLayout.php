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

class ViewCompletedChecklistItemsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'completed_checklist_items';

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
                ->render(function (ChecklistUser $checklist_user){
                    return Button::make('Undo Complete')
                        ->method('undoCompleteChecklistItem', ['checklist_user_id' => $checklist_user->id])
                        ->icon('close')
                        ->type(Color::DANGER());
                }),
            
            TD::make('title', 'Title')
                ->render(function(ChecklistUser $checklist_user){
                    return $checklist_user->checklist_item->title;
                }),
            
            TD::make('description', 'Description')
                ->render(function(ChecklistUser $checklist_user){
                    return $checklist_user->checklist_item->description;
                }),
        ];
    }
}
