<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditChecklistItemScreen extends Screen
{

    public $checklist;
    public $checklist_item;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Checklist $checklist, ChecklistItem $checklist_item): iterable
    {
        abort_if($checklist_item->checklist->id !== $checklist->id, 404);

        return [
            'checklist' => $checklist,
            'checklist_item' => $checklist_item
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Checklist Item: ' . $this->checklist_item->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Checklist Item')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this checklist item?'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.checklist-items.list', $this->checklist)
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
                Input::make('title')
                    ->title('Title')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->checklist_item->title),
                
                TextArea::make('description')
                    ->title('Description')
                    ->rows(5)
                    ->required()
                    ->horizontal()
                    ->value($this->checklist_item->description)
            ])
        ];
    }

    public function delete(Checklist $checklist, ChecklistItem $checklist_item) {
        try {
            // delete checklist
            $checklist_item->delete();

            Toast::info('You have successfully deleted the checklist item.');

            return to_route('platform.checklist-items.list', ['checklist' => $checklist_item->checklist]);
        } catch(\Exception $e) {
            Alert::error('There was an error deleting this checklist. Error Code: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Checklist $checklist, ChecklistItem $checklist_item) {
        try {
            // get fields
            $fields = $request->validate([
                'title' => 'required',
                'description' => 'required'
            ]);

            $checklist_item->update($fields);

            Toast::success('You have successfully updated ' . $checklist_item->name . '.');

            return to_route('platform.checklist-items.list', $checklist_item->checklist);
        } catch(\Exception $e) {
            Alert::error('There was an error editing this checklist. Error Code: ' . $e->getMessage());
        }
    }
}