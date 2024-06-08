<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Orchid\Layouts\ViewChecklistItemsLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
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
        return [
            'checklist' => $checklist,
            'checklist_items' => $checklist->items
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
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
            Button::make('Delete Selected')
                ->icon('trash')
                ->method('deleteChecklistItems')
                ->confirm('Are you sure you want to delete the selected checklist items?'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.checklist.list')
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
            // Checklist Items Table
            ViewChecklistItemsLayout::class,

            // Create Checklist Item
            Layout::rows([
                Input::make('title')
                    ->title('Title')
                    ->type('text')
                    ->placeholder('Ex: Find location in the Downtown area of Ottawa'),
                
                TextArea::make('description')
                    ->title('Description')
                    ->rows(5)
                    ->placeholder('Ex: Locate suitable venue within Ottawa\'s Downtown area for prom event.'),
                
                Button::make('Add')
                    ->icon('plus')
                    ->type(Color::DEFAULT())
                    ->method('createChecklistItem')
                
            ])->title('Add Checklist Item')
        ];
    }

    public function createChecklistItem(Request $request, Checklist $checklist) {
        try {
            // get fields
            $fields = $request->validate([
                'title' => 'required',
                'description' => 'required'
            ]);

            if(ChecklistItem::where('title', $fields['title'])->where('checklist_id', $checklist->id)->exists()) {
                Alert::error('That checklist item already exists for this checklist.');
                return;
            }

            $fields['checklist_id'] = $checklist->id;

            // create new checklist item
            ChecklistItem::create($fields);

            Toast::success('Checklist item created successfully');

            return to_route('platform.checklist-items.list', $checklist->id);
        } catch(\Exception $e) {
            Toast::error('There was an error creating the checklist item. Error code: ' . $e->getMessage());
        }
    }

    public function deleteChecklistItems(Request $request) {
        try {
            // get all selected checklists items
            $checklist_items = $request->get('checklist_items');

            // check for empty array
            if(!empty($checklist_items)) {
                // delete selected checklists
                ChecklistItem::whereIn('id', $checklist_items)->delete();

                Toast::success('Selected checklist items deleted succesfully.');
            } else {
                Toast::warning('Please select checklist items in order to delete them.');
            }


        } catch(\Exception $e) {

        }
    }

    public function redirect(Checklist $checklist) {
        return to_route('platform.checklist-items.edit', ['checklist' => $checklist, 'checklist_item' => request('checklist_item')]);
    }
}
