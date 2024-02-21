<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use App\Orchid\Layouts\ViewBugReportLayout;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

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
            'bug_reports' => BugReport::filter(request(['severity']))->latest('bug_reports.created_at')->paginate(10)
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
        return [
            Button::make('Delete Selected Bug Reports')
                ->icon('trash')
                ->method('deleteBugReports')
                ->confirm('Are you sure you want to delete the selected bug reports?'),
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

    public function deleteBugReports(Request $request)
    {   
        //get all bug reports from post request
        $bug_reports = $request->get('bug_reports');
        
        try{
            //if the array is not empty
            if(!empty($bug_reports)){

                //delete all bug reports in the array
                BugReport::whereIn('id', $bug_reports)->delete();

                Toast::success('Selected bug reports deleted succesfully.');

            } else {
                Toast::warning('Please select bug reports in order to delete them.');
            }

        } catch(\Exception $e) {
            Toast::error('There was a error trying to deleted the selected bug reports. Error Message: ' . $e->getMessage());
        }
    }

    public function filter() {
        return to_route('platform.bug-reports.list', request(['severity']));
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
