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
    protected $target = 'user_bug_reports';

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
                                ->route('platform.bug-reports.view', $bug_report->id);
                    }),
            
            TD::make('module', 'Module')
                    ->render(function(BugReport $bug_report) {
                        return Link::make($bug_report->toCleanModuleString())
                            ->route('platform.bug-reports.view', $bug_report->id);
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
                            ->method('redirect', ['bug_report_id' => $bug_report->id]);
                    }),

            TD::make('status', "Status")
                    ->render(function(BugReport $bug_report) {
                        $button_color = match($bug_report->status) {
                            0, 2 => Color::SUCCESS(),
                            1 => Color::WARNING(),  
                        };

                        return Button::make($bug_report->toStatusString())
                            ->type($button_color)
                            ->method('redirect', ['bug_report_id' => $bug_report->id]);
                    }),
                
            TD::make('creation_at', 'Creation Date')
                    ->render(function(BugReport $bug_report) {
                        return Link::make(date('F d, Y', $bug_report->created_at->timestamp))
                            ->route('platform.bug-reports.view', $bug_report->id);
                    }),
                
            TD::make()
                    ->render(function(BugReport $bug_report) {
                        return Button::make('View')
                                ->type(Color::DARK())
                                ->method('redirect', ['bug_report_id' => $bug_report->id, 'type' => 'view']) 
                                ->icon('eye');
                    }),
            
        ];
    }
}
