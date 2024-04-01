<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Models\ChecklistUser;
use App\Models\Localadmin;
use App\Models\Student;
use App\Models\User;
use App\Orchid\Layouts\ViewCompletedChecklistUsersLayout;
use App\Orchid\Layouts\ViewIncompleteChecklistUsersLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewChecklistUsersScreen extends Screen
{

    public $checklist;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Checklist $checklist): iterable
    {
        $completed = $this->getCompletedChecklistUsers($checklist);
        $incomplete = $this->getIncompleteChecklistUsers($checklist);

        return [
            'checklist' => $checklist,
            'completed' => $this->paginate($completed, 10, options: ['path' => "/admin/checklists/{$checklist->id}/users"]),
            'incomplete' => $this->paginate($incomplete, 10, options: ['path' => "/admin/checklists/{$checklist->id}/users"])
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View Users: ' . $this->checklist->name;
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
                ->route('platform.checklist.list'),

            Button::make('Uncheck All For Selected')
                ->icon('trash')
                ->method('removeSelected')
                ->confirm('Are you sure you want to uncheck all checklist items for the selected users?')            
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
                'Completed Checklists' => ViewCompletedChecklistUsersLayout::class,
                'Incomplete Checklists' => ViewIncompleteChecklistUsersLayout::class
            ])
           
        ];
    }

    public function removeSelected(Checklist $checklist) {
        try {
            // get user ids
            $user_ids = request('users');

            // check for empty array
            if(!empty($user_ids)) {
                ChecklistUser::whereIn('checklist_user_id', $user_ids)->where('checklist_id', $checklist->id)->delete();

                Toast::success('Successfully unchecked all items for the selected users.');

                return to_route('platform.checklist.users', $checklist->id);
            } else {
                Toast::warning('Please select users in order to uncheck all checklist items for them.');
            }
        } catch(\Exception $e) {
            Toast::error('There was an error unchecking all checklist items users. Error code: ' . $e->getMessage());
        }
    }

    public function redirect() {
        return to_route('platform.checklist.users.view', ['checklist' => request('checklistid'), 'user' => request('userid')]);
    }

    private function getCompletedChecklistUsers(Checklist $checklist) {
        $completed = [];

        // go through every user that have attempted the checklist
        foreach(array_unique(ChecklistUser::where('checklist_id', $checklist->id)->pluck('checklist_user_id')->toArray()) as $user_id) {
            $completedChecklistItems = ChecklistUser::where('checklist_id', $checklist->id)->where('checklist_user_id', $user_id)->count();
            $totalChecklistItems = count($checklist->items);

            if($completedChecklistItems == $totalChecklistItems) $completed[] = User::find($user_id);
        }

        return Collection::make($completed);
    }

    private function getIncompleteChecklistUsers(Checklist $checklist) {
        $users = $checklist->type == 0 ? Localadmin::all() : Student::all();

        return array_map(function($id) {
            return User::find($id);
        }, $users->whereNotIn('user_id', $this->getCompletedChecklistUsers($checklist)->pluck('id'))->pluck('user_id')->toArray());
    }

    // from https://laracasts.com/discuss/channels/laravel/how-to-paginate-laravel-collection
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
