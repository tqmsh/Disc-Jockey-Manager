<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use Illuminate\Support\Facades\DB;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class EditBugReportScreen extends Screen
{

    public $bug_report;

    public $module_options;

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
        return 'Edit Bug Report: ' . $this->bug_report->title;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Bug Report')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this bug report?'),
            
            Button::make('Go To Reporter')
                ->icon('user')
                ->method('redirectToUserEdit', ['bug_report' => $this->bug_report->id]),

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
            Layout::rows([
                Input::make('title')
                    ->title('Title')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->bug_report->title),
                
                TextArea::make('description')
                    ->title('Description')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->bug_report->description),
                
                Select::make('module')
                    ->title('Module')
                    ->options($this->getModuleOptions())
                    ->horizontal()
                    ->required()
                    ->value($this->bug_report->module),
                
                Select::make('severity')
                    ->title('Severity Level')
                    ->options([
                        1 => 'Critical',
                        2 => 'Moderate',
                        3 => 'Minor',
                    ])
                    ->horizontal()
                    ->required()
                    ->value($this->bug_report->severity)
            ])
        ];
    }

    public function update(BugReport $bug_report, Request $request) {
        try {
            $fields = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'module' => 'required',
                'severity' => 'required'
            ]);

            // update bug report
            $bug_report->update($fields);

            Toast::success('You have successfully updated ' . $bug_report->title . '.');

            return redirect()->route('platform.bug-reports.list');
        } catch(\Exception $e) {
            Alert::error('There was an error editing this bug report. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(BugReport $bug_report) {
        try {
            // delete bug report
            $bug_report->delete();

            Toast::info('You have successfully deleted the bug report.');

            return to_route('platform.bug-reports.list');
        } catch(\Exception $e) {
            Alert::error('There was an error deleting this bug report. Error Code: ' . $e->getMessage());
        }
    }

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

    public function getModuleOptions() : array {
        return match($this->bug_report->reporter_role) {
            // There's no teacher portal yet, we'll pass them off as a local admin
            2, 5 => [
                'localadmin.events' => 'Events',
                'localadmin.students' => 'Students',
                'localadmin.groups' => 'Groups',
                'localadmin.contracts' => 'Contracts',
                'localadmin.prom-profit' => 'Prom Profit'
            ],

            3 => [
                'student_portal.events' => 'Events',
                'student_portal.bids' => 'Bids',
                'student_portal.groups' => 'Groups',
                'student_portal.my-specs' => 'My Specs',
                'student_portal.dresses' => 'Dresses'
            ],

            4 => [
                'vendor_portal.shop' => 'Shop',
                'vendor_portal.bids' => 'Bids',
                'vendor_portal.campaigns' => 'Campaigns'
            ]
        };
    }
}
