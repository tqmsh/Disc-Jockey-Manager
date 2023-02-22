<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Seating;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\ViewStudentLayout;
use App\Orchid\Layouts\ViewSeatedStudentLayout;
use App\Orchid\Layouts\ViewUnattendingStudentLayout;

class ViewEventStudentScreen extends Screen
{
    public $event;
    public $students;
    public $seatedStudents;
    public $tables;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event,
            'students' => Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->get(['user_id']))->filter(request(['ticketstatus', 'event_id']))->paginate(20),
            'unattending_students' => Student::whereNotIn('user_id', EventAttendees::where('event_id', $event->id)->get(['user_id']))->paginate(20),
            'seatedStudents' =>  Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->whereNotNull('table_id')->get(['user_id']))
                                ->filter(request(['ticketstatus', 'event_id']))->paginate(20),
            'unseatedStudents' =>  Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->whereNull('table_id')->get(['user_id']))
                                ->filter(request(['ticketstatus', 'event_id']))->paginate(20),
            'tables' => Seating::where('event_id', $event->id),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Students: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            DropDown::make('Actions')
                ->icon('arrow-down')
                ->list([

                    Button::make('Add Selected Students to Event')
                        ->method('addStudents')
                        ->icon('plus'),

                    Button::make('Remove Selected Students From Event')
                        ->method('deleteStudents')
                        ->confirm('Are you sure you want to remove the selected students from: ' . $this->event->event_name)
                        ->icon('trash'),
                ]),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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
            Layout::modal('addStudentToTable',[

                Layout::rows([

                    Group::make([

                        Select::make('table_id')
                            ->empty('Select Table')
                            ->options(function () {
                                return $this->tables->pluck('tablename', 'id');
                            }),

                        Button::make('Add Student to Table')
                            ->icon('plus')
                            ->method('addStudentToTable')
                            ->type(Color::DEFAULT()),
                    ]),
                ]),
            ])
            ->title('Edit Seating')
            ->withoutCloseButton(),

            Layout::rows([

                Group::make([

                    Select::make('ticketstatus')
                        ->empty('Ticket Status')
                        ->options([
                            'Paid' => 'Paid',
                            'Unpaid' => 'Unpaid'
                        ]),

                    Button::make('Filter')
                        ->icon('filter')
                        ->method('filter')
                        ->type(Color::DEFAULT()),
                ]),
                    
            ]),

            Layout::tabs([
                'Attending Students' => [
                    ViewStudentLayout::class
                ],

                'Add Students' => [
                    ViewUnattendingStudentLayout::class
                ],
            ]),
            
            Layout::tabs([
                'Seated Students' => [
                    Layout::table('seatedStudents', [

                        TD::make('Edit Seating')
                            ->render(function (Student $student) {
                                return ModalToggle::make('Edit Seating')
                                        ->modal('addStudentToTable')
                                        ->method('addStudentToTable')
                                        ->icon('pencil');
                            })->width('300px'),

                        TD::make('Student Name')
                            ->render(function (Student $student) {
                                return $student->firstname . ' ' . $student->lastname;
                            }),

                        TD::make('Student Email')
                            ->render(function (Student $student) {
                                return $student->email;
                            }),

                        //table name
                        TD::make('Assigned Table')
                            ->render(function (Student $student) {
                                return e(Seating::where('id', EventAttendees::where('user_id', $student->user_id)->where('event_id', $this->event->id)->pluck('table_id'))->get(['tablename'])->value('tablename'));
                            }),

                    ])
                ],

                'Add Students to Tables' => [
                    Layout::table('unseatedStudents', [

                        TD::make('Add to Table')
                            ->render(function (Student $student) {
                                return ModalToggle::make('Add to Table')
                                        ->modal('addStudentToTable')
                                        ->method('addStudentToTable')
                                        ->icon('plus');
                            })->width('300px'),

                        TD::make('Student Name')
                            ->render(function (Student $student) {
                                return $student->firstname . ' ' . $student->lastname;
                            }),

                        TD::make('Student Email')
                            ->render(function (Student $student) {
                                return $student->email;
                            }),
                    ])                
                ],   
            ]),
        ];

    }

    public function deleteStudents(Request $request, Events $event)
    {   
        //get all students from post request
        $students = $request->get('students');
        
        try{

            
            //if the array is not empty
            if(!empty($students)){
                
                //loop through the students and delete them from db
                foreach($students as $student){
                    EventAttendees::where('user_id', $student)->where('event_id', $event->id)->delete();
                }

                Toast::success('Selected students deleted succesfully');

            }else{
                Toast::warning('Please select students in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected students. Error Message: ' . $e->getMessage());
        }
    }

    public function addStudents(Request $request, Events $event)
    {
        //get all students from post request
        $students = $request->get('unattendingStudents');

        try{

            //if the array is not empty
            if(!empty($students)){

                //loop through the students and add them to db
                foreach($students as $student){
                    EventAttendees::create([
                        'user_id' => $student,
                        'event_id' => $event->id,
                    ]);
                }

                Toast::success('Selected students added succesfully');

            }else{
                Toast::warning('Please select students in order to add them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to add the selected students. Error Message: ' . $e->getMessage());
        }
    }

    public function filter(Request $request, Events $event){
        return redirect('/admin/events/students/' . $event->id . 
            '?ticketstatus=' . $request->ticketstatus .
            '&event_id=' . $event->id
        );
    }
}
