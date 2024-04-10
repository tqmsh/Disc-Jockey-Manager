<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistUser;
use App\Orchid\Layouts\ViewCompletedChecklistItemsLayout;
use App\Orchid\Layouts\ViewIncompleteChecklistItemsLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewChecklistItemScreen extends Screen
{

    public $checklist;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Checklist $checklist): iterable
    {
        abort_if($checklist->type !== 0, 404, 'You do not have access to this checklist\'s items.');

        $completedChecklistItems = ChecklistUser::where('checklist_user_id', Auth::user()->id)->where('checklist_id', $checklist->id);

        return [
            'checklist' => $checklist,
            'completed_checklist_items' => $completedChecklistItems->latest()->get(),
            'incomplete_checklist_items' => ChecklistItem::whereNotIn('id', $completedChecklistItems->pluck('checklist_item_id'))->where('checklist_id', $checklist->id)->get()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Checklist Items: ' . $this->checklist->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Back')
                ->icon('arrow-left')
                ->method('redirect')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'Completed Checklist Items' => ViewCompletedChecklistItemsLayout::class,
                'Incomplete Checklist Items' => ViewIncompleteChecklistItemsLayout::class
            ]),
        ];
    }

    public function redirect() {
        return to_route('platform.checklist.list');
    }

    public function completeChecklistItem(Checklist $checklist) {
        try {
            $checklist_item_id = request('checklist_item_id');
            
            // if they already completed the checklist item, stop them.
            if(ChecklistUser::where('checklist_user_id', Auth::user()->id)->where('checklist_item_id', $checklist_item_id)->exists()){
                Alert::error('You have already completed this checklist item.');

                return to_route('platform.checklist-items.list', ['checklist' => $checklist->id]);
            }

            // create new row in ChecklistUser
            ChecklistUser::create([
                'checklist_id' => $checklist->id,
                'checklist_item_id' => $checklist_item_id,
                'checklist_user_id' => Auth::user()->id
            ]);

            Toast::success('Successfully completed the checklist item.');

            return to_route('platform.checklist-items.list', ['checklist' => $checklist->id]);
        } catch(\Exception $e) {
            Alert::error('There was an error completing this checklist item. Error Code: ' . $e->getMessage());
        }
    }

    public function undoCompleteChecklistItem() {
        try {
            $checklist_user = ChecklistUser::find(request('checklist_user_id'));

            if(is_null($checklist_user)){
                Alert::error('You already removed this checklist item from the "completed" section.');
                return;
            }

            $checklist_user->delete();

            Toast::success('Successfully removed this checklist item from the "completed" section.');

            return to_route('platform.checklist-items.list', ['checklist' => $checklist_user->checklist->id]);
        } catch(\Exception $e) {
            Alert::error('There was an error removing this checklist item from the "completed" section. Error Code: ' . $e->getMessage());
        }
    }
}
