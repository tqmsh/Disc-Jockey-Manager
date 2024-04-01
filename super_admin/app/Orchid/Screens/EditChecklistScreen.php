<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditChecklistScreen extends Screen
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
            'checklist' => $checklist
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Checklist: ' . $this->checklist->name;
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

            Button::make('Delete Checklist')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this checklist?'),

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
            Layout::rows([
                Input::make('name')
                    ->title('Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->checklist->name),
                
                TextArea::make('description')
                    ->title('Description')
                    ->rows(5)
                    ->required()
                    ->horizontal()
                    ->value($this->checklist->description),
                
                Select::make('type')
                    ->title('Type')
                    ->required()
                    ->horizontal()
                    ->options([
                        0 => 'Prom Committee Checklist',
                        1 => 'Student Checklist'
                    ])
                    ->value($this->checklist->type)
            ])
        ];
    }

    public function delete(Checklist $checklist) {
        try {
            // delete checklist
            $checklist->delete();

            Toast::info('You have successfully deleted the checklist.');

            return to_route('platform.checklist.list');
        } catch(\Exception $e) {
            Alert::error('There was an error deleting this checklist. Error Code: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Checklist $checklist) {
        try {
            // get fields
            $fields = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|in:0,1'
            ]);

            $checklist->update($fields);

            Toast::success('You have successfully updated ' . $checklist->name . '.');

            return to_route('platform.checklist.list');
        } catch(\Exception $e) {
            Alert::error('There was an error editing this checklist. Error Code: ' . $e->getMessage());
        }
    }
}
