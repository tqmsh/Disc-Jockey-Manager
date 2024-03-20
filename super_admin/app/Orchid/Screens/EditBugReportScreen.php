<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

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
                    ->value($this->bug_report->severity),
                
                Select::make('status')
                    ->title('Status')
                    ->options([
                        0 => 'New',
                        1 => 'Under Review',
                        2 => 'Fixed',
                    ])
                    ->horizontal()
                    ->required()
                    ->value($this->bug_report->status)
            ])
        ];
    }

    public function update(BugReport $bug_report, Request $request) {
        try {
            $fields = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'module' => 'required',
                'severity' => 'required',
                'status' => 'required'
            ]);

            $old_title = $bug_report->title;
            $old_status = $bug_report->status;

            // update bug report
            $bug_report->update($fields);

            // send notification to user when status gets changed to fixed
            if($bug_report->status !== $old_status && $bug_report->toStatusString() == 'Fixed') {
                $notification = new GeneralNotification([
                    'title' => 'Bug Report Status Updated',
                    'message' => "The status of your bug report: {$old_title}, has been set to Fixed.",
                    'action' => "/admin/bug-reports/{$bug_report->id}"
                ]);

                User::find($bug_report->reporter_user_id)->notify($notification);

                // send email to user to tell them the bug report has been fixed
                $this->sendEmail($bug_report);
            }

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

    public function sendEmail(BugReport $bug_report) {
        $bug_reporter = User::find($bug_report->reporter_user_id);
        $name = $bug_reporter->name;

        $emailContent = "Dear {$name},

        We are sending this email to notify you that the status of your bug report: {$bug_report->title}, has been set to Fixed.

        Thank you for submitting a bug report that allows us to improve Prom Planner for better user experience.
        
        Best regards,
        
        Prom Planner Team";

        $emailData = [
            'subject' => 'Your bug report has been fixed!',
            'content' => $emailContent
        ];

        Mail::send(
            'emails.bugReportEmail', $emailData, 
            function (Message $message) use ($bug_reporter, $emailData) {
                $message->subject($emailData['subject']);
                $message->to($bug_reporter->email);
        });
    }
}
