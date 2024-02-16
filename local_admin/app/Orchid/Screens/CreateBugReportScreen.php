<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateBugReportScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        abort_if(Auth::user()->role != 2, 403, 'You are not authorized to view this page.');

        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create a bug report';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Bug Report')
                ->icon('plus')
                ->method('createBugReport')
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
                    ->placeholder('Enter a brief title about the bug')
                    ->horizontal()
                    ->required(),

                TextArea::make('description')
                    ->title('Description')
                    ->placeholder('Enter a description about the bug')
                    ->rows(5)
                    ->horizontal()
                    ->required(),
                
                Select::make('module')
                    ->title('Module')
                    ->placeholder('Which module did you find the bug in?')
                    ->options([
                        'localadmin.events' => 'Events',
                        'localadmin.students' => 'Students',
                        'localadmin.groups' => 'Groups',
                        'localadmin.contracts' => 'Contracts',
                        'localadmin.prom-profit' => 'Prom Profit'
                    ])
                    ->horizontal()
                    ->required(),

                Select::make('severity')
                    ->title('Severity Level')
                    ->placeholder('How severe was the bug?')
                    ->options([
                        1 => 'Critical',
                        2 => 'Moderate',
                        3 => 'Minor',
                    ])
                    ->horizontal()
                    ->required()
            ])
        ];
    }

    public function createBugReport(Request $request) {
        try {
            // get fields
            $fields = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'module' => 'required',
                'severity' => 'required'
            ]);

            $fields['reporter_user_id'] = Auth::user()->id;
            $fields['reporter_role'] = Auth::user()->role;

            // create new bug report
            BugReport::create($fields);

            Toast::success('Thank you for reporting a bug! We always appreciate your feedback and efforts to help us improve Prom Planner.');

            return to_route('platform.main');
        } catch(Exception $e) {
            Toast::error('There was an error creating the bug report. Error code: ' . $e->getMessage());
        }
    }
}
