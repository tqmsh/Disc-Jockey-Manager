<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use App\Models\User;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

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

                Sight::make('module')
                    ->render(fn(BugReport $bug_report) => $bug_report->toCleanModuleString()),

                Sight::make('reporter_first_name', 'Reporter First Name')
                    ->render(fn(BugReport $bug_report) => User::find($bug_report->reporter_user_id)->firstname),

                Sight::make('reporter_last_name', 'Reporter Last Name')
                    ->render(fn(BugReport $bug_report) => User::find($bug_report->reporter_user_id)->lastname),

                Sight::make('reporter_email', 'Reporter Email')
                    ->render(fn(BugReport $bug_report) => User::find($bug_report->reporter_user_id)->email),

                Sight::make('reporter_role', 'Reporter Role')
                    ->render(function(BugReport $bug_report){
                        $user_role = User::find($bug_report->reporter_user_id)->role;

                        return Role::find($user_role)->slug;
                    }),

                Sight::make('reporter_phone_number', 'Reporter Phone Number')
                    ->render(fn(BugReport $bug_report) => User::find($bug_report->reporter_user_id)->phone_number ?? "None")
            ])
        ];
    }
}
