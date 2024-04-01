<?php

namespace App\Orchid\Screens;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Toast;

class CreateChecklistScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add a New Checklist';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Checklist')
                ->icon('plus')
                ->method('createChecklist'),

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
                    ->placeholder('Ex: Venue Selection and Booking')
                    ->horizontal(),

                TextArea::make('description')
                    ->title('Description')
                    ->placeholder('Ex: Finalize prom venue choice.')
                    ->rows(5)
                    ->horizontal()
                    ->required(),

                Select::make('type')
                    ->title('Type')
                    ->placeholder('Who should be given the checklist?')
                    ->options([
                        0 => 'Prom Committee Checklist',
                        1 => 'Student Checklist'
                    ])
                    ->horizontal()
                    ->required()
            ])
        ];
    }

    public function createChecklist(Request $request) {
        try {
            // get fields
            $fields = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|in:0,1'
            ]);

            // create new checklist
            Checklist::create($fields);          

            Toast::success('Checklist created successfully');

            return to_route('platform.checklist.list');
        } catch(\Exception $e) {
            Toast::error('There was an error creating the checklist. Error code: ' . $e->getMessage());
        }
    }
}
