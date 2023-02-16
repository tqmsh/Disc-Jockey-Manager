<?php

namespace App\Orchid\Screens;

use Locale;
use Exception;
use App\Models\User;
use App\Models\Events;
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
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Relation;
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
            'students' => Student::filter(request(['sort_option','event_id', 'ticketstatus']))->latest('students.created_at')
                        ->where('school_id', Localadmin::where('user_id', Auth::user()->id)->pluck('school_id'))
                        ->where('students.account_status', 1)->paginate(20)
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

                    Select::make('sort_option')
                        ->title('Order Students By:')
                        ->empty('No selection')
                        ->help('Start typing in boxes to search')
                        ->options([
                            'firstname' => 'First Name',
                            'lastname' => 'Last Name',
                            'grade' => 'Grade'
                        ]),

                    Select::make('event_id')
                        ->title('Event:')
                        ->empty('No selection')
                        ->fromQuery(Events::query()->where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id')), 'event_name'),

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
                
            ViewStudentLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/students?'
                    .'&sort_option=' . $request->get('sort_option')
                    .'&ticketstatus=' . $request->get('ticketstatus') 
                    .'&event_id=' . $request->get('event_id') 
                );
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
                    User::where('id', $student)->delete();
                }

                Toast::success('Selected students deleted succesfully');

            }else{
                Toast::warning('Please select students in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected students. Error Message: ' . $e);
        }
    }
}

