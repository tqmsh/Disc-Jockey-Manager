<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use App\Orchid\Layouts\ViewBugReportLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;

class ViewBugReportScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'user_bug_reports' => BugReport::filter(request(['severity', 'status']))->where('reporter_user_id', Auth::user()->id)->paginate(min(request()->query('pagesize', 10), 100))->sortByDesc('created_at')
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Report a Bug';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create Bug Report')
                ->icon('plus')
                ->route('platform.bug-reports.create')
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
                Group::make([
                    Select::make('severity')
                        ->title('Severity:')
                        ->empty('No Selection')
                        ->options([
                            1 => 'Critical',
                            2 => 'Moderate',
                            3 => 'Minor'
                        ]),
                    
                    Select::make('status')
                        ->title('Status:')
                        ->empty('No Selection')
                        ->options([
                            0 => 'New',
                            1 => 'Under Review',
                            2 => 'Fixed'
                        ])
                ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewBugReportLayout::class
        ];
    }

    public function filter() {
        return to_route('platform.bug-reports.list', request(['severity', 'status']));
    }

    public function redirect($bug_report_id) {
        return to_route('platform.bug-reports.view', $bug_report_id);
    }
}