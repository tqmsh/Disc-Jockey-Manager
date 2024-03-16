<?php

namespace App\Orchid\Screens;

use App\Models\BugReport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Mail\Message;

class CreateBugReportScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        abort_if(Auth::user()->role != 4, 403, 'You are not authorized to view this page.');

        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create a Bug Report';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Bug Report')
                ->icon('plus')
                ->method('createBugReport')
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
                    ->placeholder('Enter a brief title about the bug')
                    ->horizontal()
                    ->required(),

                TextArea::make('description')
                    ->title('Description')
                    ->placeholder('Enter a description about the bug')
                    ->rows(5)
                    ->horizontal()
                    ->required(),
                
                Select::make('module')
                    ->title('Module')
                    ->placeholder('Which module did you find the bug in?')
                    ->options([
                        'vendor_portal.shop' => 'Shop',
                        'vendor_portal.bids' => 'Bids',
                        'vendor_portal.campaigns' => 'Campaigns'
                    ])
                    ->horizontal()
                    ->required(),

                Select::make('severity')
                    ->title('Severity Level')
                    ->placeholder('How severe was the bug?')
                    ->options([
                        1 => 'Critical',
                        2 => 'Moderate',
                        3 => 'Minor',
                    ])
                    ->horizontal()
                    ->required()
            ])
        ];
    }

    public function createBugReport(Request $request) {
        try {
            // get fields
            $fields = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'module' => 'required',
                'severity' => 'required'
            ]);

            $fields['reporter_user_id'] = Auth::user()->id;
            $fields['reporter_role'] = Auth::user()->role;

            // create new bug report
            $bug_report = BugReport::create($fields);          

            // send email to user
            $this->sendEmail($bug_report);

            Toast::success('Thank you for reporting a bug! We always appreciate your feedback and efforts to help us improve Prom Planner.');

            return to_route('platform.bug-reports.list');
        } catch(Exception $e) {
            Toast::error('There was an error creating the bug report. Error code: ' . $e->getMessage());
        }
    }

    public function sendEmail(BugReport $bug_report) {
        $name = Auth::user()->name;

        $emailContent = "Dear {$name},

        We are sending this email to notify you that we have successfully received your bug report: {$bug_report->title}.

        Thank you for taking the time to submit a bug report for Prom Planner. Your contribution helps us improve our app for all users.

        An administrator will be reviewing your bug report shortly. If you have any further information or details to add, please do not hesitate to contact us at info@promplanner.app.
        
        We appreciate your support in making Prom Planner better.
        
        Best regards,
        
        Prom Planner Team";

        $emailData = [
            'subject' => 'Thank you for submitting a bug report!',
            'content' => $emailContent
        ];

        Mail::send(
            'emails.bugReportEmail', $emailData, 
            function (Message $message) use ($emailData) {
                $message->subject($emailData['subject']);
                $message->to(Auth::user()->email);
        });
    }
}
