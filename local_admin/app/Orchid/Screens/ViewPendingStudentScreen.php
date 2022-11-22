<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
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
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_name', 'school_name'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(User::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewPendingStudentLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/pendinglocaladmins?' 
                    .'&school=' . $request->get('school')
                    .'&country=' . $request->get('country')
                    .'&school_board=' . $request->get('school_board')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function acceptStudents(Request $request){

        //get all students from post request
        $students = $request->get('students');
        
        try{
            //if the array is not empty
            if(!empty($students)){

                //loop through the students set account status to 1 and give them permissions to access dashboard
                foreach($students as $student_id){

                    $userTableFields = [
                        'account_status' => 1,
                        'permissions' => '{"platform.index":true}'
                    ];

                    $studentTableFields = [
                        'account_status' => 1,
                    ];

                    User::where('id', $student_id)->update($userTableFields);
                    Student::where('user_id', $student_id)->update($studentTableFields);
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

                Toast::success('Selected local admins deleted succesfully');

            }else{
                Toast::warning('Please select local admins in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected local admins. Error Message: ' . $e);
        }
    }
}
