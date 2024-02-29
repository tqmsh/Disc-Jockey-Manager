<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class ViewBugReportDetailedScreen extends Screen
{

    public $bug_report;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(BugReport $bug_report): iterable
    {
        // Check for valid role and valid user
        abort_if(Auth::user()->role != 2 || Auth::user()->id != $bug_report->reporter_user_id, 403, 'You are not authorized to view this page.');

        return [
            'bug_report' => $bug_report
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bug Report: ' . $this->bug_report->title;
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
                ->route('platform.bug-reports.list')
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
            Layout::legend('bug_report', [
                Sight::make('id', 'ID'),

                Sight::make('title'),

                Sight::make('description'),

                Sight::make('severity')
                    ->render(fn(BugReport $bug_report) => $bug_report->toSeverityString()),

                Sight::make('status')
                    ->render(fn(BugReport $bug_report) => $bug_report->toStatusString()),

                Sight::make('module')
                    ->render(fn(BugReport $bug_report) => $bug_report->toCleanModuleString())
            ])
        ];
    }
}
