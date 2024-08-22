<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewStaffLayout;

class ViewStaffScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'students' => Student::filter(request(['country', 'state_province', 'school', 'sort_option', 'school_board', 'event_id', 'ticketstatus']))->latest('students.created_at')
            ->where('students.account_status', 1)->paginate(min(request()->query('pagesize', 10), 100))
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
                        ->help('Type in boxes to search')
                        ->options([
                            'firstname' => 'First Name',
                            'lastname' => 'Last Name',
                            'grade' => 'Grade'
                        ]),

                    Select::make('ticketstatus')
                        ->title('Ticket Status')
                        ->empty('No selection')
                        ->options([
                            'Paid' => 'Paid',
                            'Unpaid' => 'Unpaid'
                        ]),

                    Select::make('event_id')
                        ->title('Event:')
                        ->empty('No Selection')
                        ->fromQuery(Events::query(), 'event_name'),
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('No Selection')
                        ->fromModel(Student::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No Selection')
                        ->fromModel(User::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No Selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No Selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewStaffLayout::class
        ];
    }

    public function filter(){

        return redirect()->route('platform.student.list', request(['ticketstatus', 'sort_option', 'school', 'country', 'school_board', 'event_id', 'state_province']));
    }


    public function redirect($student){
        return redirect()-> route('platform.student.edit', $student);
    }


    public function deleteStudents(Request $request)
    {   
        //get all students from post request
        $students = $request->get('students');
        
        try{

            //if the array is not empty
            if(!empty($students)){

                User::whereIn('id', $students)->delete();

                Alert::success('Selected students deleted succesfully');

            }else{
                Alert::warning('Please select students in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected students. Error Message: ' . $e->getMessage());
        }
    }
}

