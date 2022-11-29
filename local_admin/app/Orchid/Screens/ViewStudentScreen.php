<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewStudentLayout;

class ViewStudentScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'students' => Student::where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->latest('students.created_at')
            ->filter(request(['country', 'state_province', 'school', 'school_board', 'ticketstatus']))
            ->where('students.account_status', 1)->paginate(10)
        ];
    }


    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Students';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Students')
                ->icon('plus')
                ->route('platform.student.create'),

            Button::make('Delete Selected Students')
                ->icon('trash')
                ->method('deleteStudents')
                ->confirm(__('Are you sure you want to delete the selected students?')),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list')
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

                    Select::make('ticketstatus')
                        ->title('Ticket Status')
                        ->empty('No selection')
                        ->options([
                            'Paid' => 'Paid',
                            'Unpaid' => 'Unpaid'
                        ]),
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('No selection')
                        ->fromModel(Student::class, 'school', 'school'),

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

            ViewStudentLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/students?'
                    .'&ticketstatus=' . $request->get('ticketstatus') 
                    .'&school=' . $request->get('school')
                    .'&country=' . $request->get('country')
                    .'&school_board=' . $request->get('school_board')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function deleteStudents(Request $request)
    {   
        //get all students from post request
        $students = $request->get('students');
        
        try{

            //if the array is not empty
            if(!empty($students)){

                //loop through the students and delete them from db
                foreach($students as $student){
                    Student::where('id', $student)->delete();
                }

                Alert::success('Selected students deleted succesfully');

            }else{
                Alert::warning('Please select students in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected students. Error Message: ' . $e);
        }
    }
}

