<?php

namespace App\Orchid\Layouts;

use App\Models\Checklist;
use App\Models\ChecklistUser;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewChecklistLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'checklists';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (Checklist $checklist){
                    return CheckBox::make('checklists[]')
                        ->value($checklist->id)
                        ->checked(false);
                }),
            
            TD::make('name', 'Name')
                ->render(function(Checklist $checklist) {
                    return Link::make($checklist->name)
                        ->route('platform.checklist-items.list', $checklist);
                }),

            TD::make('description', 'Description')
                ->render(function(Checklist $checklist) {
                    return Link::make($checklist->description)
                        ->route('platform.checklist-items.list', $checklist);
                }),
            
            TD::make('completed', 'Completed')
                ->render(function(Checklist $checklist) {
                    $completedChecklistItems = ChecklistUser::where('checklist_user_id', Auth::user()->id)->where('checklist_id', $checklist->id)->count();
                    $totalChecklistItems = count($checklist->items);
                    return Link::make("{$completedChecklistItems}/{$totalChecklistItems}")
                        ->route('platform.checklist-items.list', $checklist);
                }),
            
            TD::make('creation_at', 'Creation Date')
                    ->render(function(Checklist $checklist) {
                        return Link::make(date('F d, Y', $checklist->created_at->timestamp))
                            ->route('platform.checklist-items.list', $checklist);
                    }),
            

            TD::make()
                ->render(function(Checklist $checklist) {
                    return Button::make('View Checklist Items')
                            ->type(Color::DARK())
                            ->method('redirect', ['checklist_id' => $checklist->id])
                            ->icon('eye');
                }),
        ];
    }
}