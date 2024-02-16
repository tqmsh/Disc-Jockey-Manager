<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use App\Orchid\Layouts\ViewBugReportLayout;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\DB;

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
            'bug_reports' => BugReport::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bug Reports';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewBugReportLayout::class
        ];
    }

    public function to_route($route, $bug_report_id) { return to_route($route, $bug_report_id); }

    // Redirects the admin the to user's respective edit page (e.g local admin edit page or student edit page)
    public function redirectToUserEdit(BugReport $bug_report) {
        // There's no teacher portal yet, we'll pass them off as a local admin
        $reporter_role = match($bug_report->reporter_role) {
            2, 5 => "Local Admin",
            3 => "Student",
            4 => "Vendor"
        };

        $safe_role = str_replace(' ', '', strtolower($reporter_role));

        $id = DB::table($safe_role . 's')
            ->where('user_id', $bug_report->reporter_user_id)
            ->get('id')
            ->first()
            ->id;
        
        $route = 'platform.' . $safe_role . '.edit';

        return to_route($route, $id);
    }
}
