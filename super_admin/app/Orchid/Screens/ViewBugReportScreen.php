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
            'bug_reports' => BugReport::filter(request(['severity', 'status']))->latest('bug_reports.created_at')->paginate(10)
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
                        ]),
                    
                    Select::make('status')
                        ->title('Status:')
                        ->empty('No Selection')
                        ->options([
                            0 => 'New',
                            1 => 'Under Review',
                            2 => 'Fixed'
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
        return to_route('platform.bug-reports.list', request(['severity', 'status']));
    }

    public function redirect($bug_report_id, string $redirect_type) {
        return match(strtolower($redirect_type)) {
            'edit' => to_route('platform.bug-reports.edit', $bug_report_id),
            'view' => to_route('platform.bug-reports.view', $bug_report_id)
        };
    }
}
