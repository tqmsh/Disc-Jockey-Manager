<?php

namespace App\Orchid\Layouts;

use App\Models\BugReport;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Screen\Fields\CheckBox;

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
            TD::make()
                ->render(function (BugReport $bug_report){
                    return CheckBox::make('bug_reports[]')
                        ->value($bug_report->id)
                        ->checked(false);
            }),
            
            TD::make('title', 'Title')
                ->render(function(BugReport $bug_report) {
                    return Link::make($bug_report->title)
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
            
            TD::make('creation_at', 'Creation Date')
                ->render(function(BugReport $bug_report) {
                    return Link::make(date('F d, Y', $bug_report->created_at->timestamp))
                        ->route('platform.bug-reports.edit', $bug_report->id);
            }),
            
            TD::make('View')
                ->render(function(BugReport $bug_report) {
                    return Link::make('View')
                        ->route('platform.bug-reports.view', $bug_report->id);
            })
            
        ];
    }
}
