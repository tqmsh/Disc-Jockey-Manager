<?php

namespace App\Orchid\Layouts;

use App\Models\Roles;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistUser;

class ViewUserChecklistItemsLayout extends Table
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
            TD::make()
                ->render(function (ChecklistItem $checklist_item){
                    return CheckBox::make('checklist_items[]')
                        ->value($checklist_item->id)
                        ->checked(false);
                }),
            
            TD::make('title', 'Title')
                ->render(function(ChecklistItem $checklist_item){
                    return e($checklist_item->title);
                }),
            
            TD::make('description', 'Description')
                ->render(function(ChecklistItem $checklist_item){
                    return e($checklist_item->description);
                }),
            
            TD::make('completed', 'Completed')
                ->render(function(ChecklistItem $checklist_item){
                    $completed = ChecklistUser::where('checklist_item_id', $checklist_item->id)->where('checklist_user_id', request('user')->id)->exists();

                    return $completed ? 'Yes' : 'No';
                }),
            
            TD::make('created_at', 'Completed At')
        ];
    }
}
