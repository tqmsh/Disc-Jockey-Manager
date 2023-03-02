<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\School;
use App\Models\Student;
use App\Models\RoleUsers;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewPendingStudentLayout;

class ViewPendingStudentScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pending_students' => Student::latest('students.created_at')
                                ->filter(request(['country', 'state_province', 'school', 'school_board']))
                                ->where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))
                                ->where('students.account_status', 0)->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Pending Students';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Accept Selected Students')
                ->icon('check')
                ->method('acceptStudents')
                ->confirm(__('Are you sure you want to accept the selected students?')),

            Button::make('Reject Selected Students')
                ->icon('trash')
                ->method('deleteStudents')
                ->confirm(__('Are you sure you want to delete the selected students?')),
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

                    Select::make('sort_option')
                        ->title('Order Students By:')
                        ->empty('No selection')
                        ->help('Start typing in boxes to search')
                        ->options([
                            'firstname' => 'First Name',
                            'lastname' => 'Last Name',
                            'grade' => 'Grade'
                        ]),

                    Select::make('event_name')
                        ->title('Event:')
                        ->empty('No selection')
                        ->fromQuery(Events::where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id')), 'event_name'),

                    Select::make('ticketstatus')
                        ->title('Ticket Status')
                        ->empty('No selection')
                        ->options([
                            'Paid' => 'Paid',
                            'Unpaid' => 'Unpaid'
                        ]),
                ]),
                    
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),
            ViewPendingStudentLayout::class
        ];
    }

    public function filter(){
        return redirect()->route('platform.pendingstudent.list', request(['ticketstatus', 'school', 'country', 'school_board', 'event_id', 'state_province']));
    }

    public function acceptStudents(Request $request){

        //get all students from post request
        $students = $request->get('students');
        
        try{
            //if the array is not empty
            if(!empty($students)){

                //loop through the students set account status to 1 and give them permissions to access dashboard
                foreach($students as $student_id){

                    User::where('id', $student_id)->update(['account_status' => 1]);

                    Student::where('user_id', $student_id)->update(['account_status' => 1]);

                    RoleUsers::create([
                        'user_id' => $student_id,
                        'role_id' => 3,
                    ]);
                }

                Toast::success('Selected students accepted succesfully');

            }else{
                Toast::warning('Please select students in order to accept them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to accept the selected students. Error Message: ' . $e);
        }
    }

    public function deleteStudents(Request $request){  

        //get all students from post request
        $students = $request->get('students');
        
        try{

            //if the array is not empty
            if(!empty($students)){

                //loop through the students and delete them from db
                foreach($students as $student_id){
                    Student::where('user_id', $student_id)->delete();
                }

                Toast::success('Selected students deleted succesfully');

            }else{
                Toast::warning('Please select students in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected students. Error Message: ' . $e);
        }
    }
}
