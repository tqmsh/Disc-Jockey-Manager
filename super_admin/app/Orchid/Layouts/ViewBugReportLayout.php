<?php

namespace App\Orchid\Layouts;

use App\Models\BugReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewBugReportLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'bug_reports';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(function(BugReport $bug_report) {
                    return Link::make($bug_report->title)
                        ->route('platform.bug-reports.edit', $bug_report->id);
                }),
            
            TD::make('description', 'Description')
                ->render(function(BugReport $bug_report) {
                    return Link::make($bug_report->description)
                        ->route('platform.bug-reports.edit', $bug_report->id);
                }),
            
            TD::make('module', 'Module')
                ->render(function(BugReport $bug_report) {
                    return Link::make($bug_report->module)
                        ->route('platform.bug-reports.edit', $bug_report->id);
                }),
            
            TD::make('severity', "Severity")
                ->render(function(BugReport $bug_report) {
                    $button_color = match($bug_report->severity) {
                        1 => Color::DANGER(),
                        2 => Color::WARNING(),
                        3 => Color::SUCCESS()
                    };

                    return Button::make($bug_report->toSeverityString())
                        ->type($button_color)
                        ->method('to_route', ['route' => 'platform.bug-reports.edit', 'bug_report_id' => $bug_report->id]);
                }),
            
            TD::make('reporter', 'Reporter Role')
                ->render(function(BugReport $bug_report) {
                    // There's no teacher portal yet, we'll pass them off as a local admin
                    $reporter_role = match($bug_report->reporter_role) {
                        2, 5 => "Local Admin",
                        3 => "Student",
                        4 => "Vendor"
                    };

                    return Link::make($reporter_role)
                            ->route('platform.bug-reports.edit', $bug_report->id);
                }),
            
            TD::make('reporter', 'Reporter')
                ->render(function(BugReport $bug_report) {
                    return Button::make('Go To User')
                            ->method('redirectToUserEdit', ['bug_report' => $bug_report->id]);
                }),
            
            TD::make('creation_at', 'Creation Date')
                ->render(function(BugReport $bug_report) {
                    return Link::make(date('F d, Y'))
                        ->route('platform.bug-reports.edit', $bug_report->id);
                })
            
        ];
    }
}
