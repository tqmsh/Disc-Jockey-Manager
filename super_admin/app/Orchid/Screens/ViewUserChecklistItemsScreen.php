<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistUser;
use App\Models\User;
use App\Orchid\Layouts\ViewUserChecklistItemsLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ViewUserChecklistItemsScreen extends Screen
{

    public $user;
    public $checklist;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Checklist $checklist, User $user): iterable
    {
        return [
            'user' => $user,
            'checklist' => $checklist,
            'checklist_items' => ChecklistItem::where('checklist_id', $checklist->id)->latest()->paginate(request()->query('pagesize', 10))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        $full_name = $this->user->firstname . ' ' . $this->user->lastname;
        return 'Checklists Items : ' . $full_name;
    }

    public function description() : ?string {
        return 'Checklist: ' . $this->checklist->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.checklist.users', $this->checklist->id),
            
            Button::make('Uncheck Selected')
                ->icon('toggle-off')
                ->method('uncheckSelectedItems'),

            Button::make('Check Selected')
                ->icon('toggle-on')
                ->method('checkSelectedItems'),

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
            ViewUserChecklistItemsLayout::class
        ];
    }

    public function uncheckSelectedItems() {
        try {
            $checklist_items = request('checklist_items');

            if(!empty($checklist_items)) {
                foreach($checklist_items as $checklist_item_id) {
                    $query = ChecklistUser::where('checklist_item_id', $checklist_item_id)->where('checklist_user_id', request('user'));
                    if(!$query->exists()) continue;

                    $query->delete();
                }
                
                Toast::success('Successfully unchecked all selected checklist items.');

                return to_route('platform.checklist.users.view', ['checklist' => request('checklist'), 'user' => request('user')]);
            } else {
                Toast::error('Please select checklist items in order to uncheck them.');
            }
        } catch(\Exception $e) {
            Toast::error('There was an error unchecking the selected checklist items. Error code: ' . $e->getMessage());
        }
    }

    public function checkSelectedItems() {
        try {
            $checklist_items = request('checklist_items');

            if(!empty($checklist_items)) {
                foreach($checklist_items as $checklist_item_id) {
                    $query = ChecklistUser::where('checklist_item_id', $checklist_item_id)->where('checklist_user_id', request('user'));
                    if($query->exists()) continue;

                    ChecklistUser::create([
                        'checklist_id' => request('checklist'),
                        'checklist_item_id' => $checklist_item_id,
                        'checklist_user_id' => request('user')
                    ]);
                }
                
                Toast::success('Successfully checked all selected checklist items.');

                return to_route('platform.checklist.users.view', ['checklist' => request('checklist'), 'user' => request('user')]);
            } else {
                Toast::error('Please select checklist items in order to check them.');
            }
        } catch(\Exception $e) {
            Toast::error('There was an error check the selected checklist items. Error code: ' . $e->getMessage());
        }
    }
}
