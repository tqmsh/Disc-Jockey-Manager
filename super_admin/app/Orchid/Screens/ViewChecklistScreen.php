<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Orchid\Layouts\ViewChecklistLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;

class ViewChecklistScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'checklists' => Checklist::filter(request(['type']))->latest()->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Checklists';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Delete Selected')
                ->icon('trash')
                ->method('deleteChecklists')
                ->confirm('Are you sure you want to delete the selected checklists?'),

            Link::make('Create Checklist')
                ->icon('plus')
                ->route('platform.checklist.create'),
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
            Layout::rows([
                Select::make('type')
                    ->title('Type:')
                    ->empty('No Selection')
                    ->options([
                        0 => 'Prom Committee Checklist',
                        1 => 'Student Checklist'
                    ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewChecklistLayout::class
        ];
    }

    public function deleteChecklists(Request $request) {
        try {
            // get all selected checklists
            $checklists = $request->get('checklists');

            // check for empty array
            if(!empty($checklists)) {
                // delete selected checklists
                Checklist::whereIn('id', $checklists)->delete();

                Toast::success('Selected checklists deleted succesfully.');
            } else {
                Toast::warning('Please select checklists in order to delete them.');
            }

        } catch(\Exception $e) {
            Toast::error('There was a error trying to deleted the selected checklists. Error Message: ' . $e->getMessage());
        }
    }

    public function redirect($checklist_id, string $redirect_type) {
        return match(strtolower($redirect_type)) {
            'edit' => to_route('platform.checklist.edit', $checklist_id),
            'view' => to_route('platform.checklist-items.list', $checklist_id),
            'users' => to_route('platform.checklist.users', $checklist_id)
        };
    }

    public function filter() {
        return to_route('platform.checklist.list', request(['type']));
    }
}
