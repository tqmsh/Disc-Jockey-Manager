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
                                ->filter(request(['ticketstatus', 'event_id', 'tablename']))->paginate(20),
            'unseatedStudents' =>  Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->whereNull('table_id')->get(['user_id']))
                                ->filter(request(['ticketstatus', 'event_id']))->paginate(20),
            'tables' => Seating::where('event_id', $event->id)->paginate(10),
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
                                $tables = $this->tables->pluck('tablename', 'id');
                                $tables['remove'] = 'Remove From Current Table';
                                return $tables;
                            }),
                    ]),
                ]),
            ])
            ->title('Edit Seating')            
            ->applyButton('Update Seating')
            ->withoutCloseButton(),

            Layout::modal('editTable',[

                Layout::rows([

                    Group::make([

                        Input::make('tablename')
                            ->title('Updated Table Name')
                            ->placeholder('Table Name')
                            ->help('Enter the new name of the current table you wish to update.')
                            ->required(),
                    ]),
                ]),
            ])
            ->title('Edit table')            
            ->applyButton('Update Table')
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
                                        ->method('addStudentToTable', ['user_id' => $student->user_id])
                                        ->icon('pencil');
                            }),

                        //table name
                        TD::make('Assigned Table')
                            ->render(function (Student $student) {
                                return e(Seating::where('id', EventAttendees::where('user_id', $student->user_id)->where('event_id', $this->event->id)->pluck('table_id'))->get(['tablename'])->value('tablename'));
                            }),

                        TD::make('Student Name')
                            ->render(function (Student $student) {
                                return $student->firstname . ' ' . $student->lastname;
                            }),

                        TD::make('Student Email')
                            ->render(function (Student $student) {
                                return $student->email;
                            }),

                        TD::make('Phone Number')
                            ->render(function (Student $student) {
                                return $student->phonenumber;
                            }),

                    ])
                ],


                'Add Students to Tables' => [
                    Layout::table('unseatedStudents', [

                        TD::make('Add to Table')
                            ->render(function (Student $student) {
                                return ModalToggle::make('Add to Table')
                                        ->modal('addStudentToTable')
                                        ->method('addStudentToTable', ['user_id' => $student->user_id])
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
                        
                        TD::make('Phone Number')
                            ->render(function (Student $student) {
                                return $student->phonenumber;
                            }),
                    ])                
                ],
                
                'Tables' => [

                    Layout::table('tables', [

                        TD::make('Table Name')
                            ->render(function (Seating $table) {
                                return e($table->tablename);
                            })->width('300px'),

                        TD::make('Created At')
                            ->render(function (Seating $table) {
                                return e($table->created_at);
                            }),

                        TD::make('Updated At')
                            ->render(function (Seating $table) {
                                return e($table->updated_at);
                            }),

                        TD::make('Edit Table')
                            ->render(function (Seating $table) {
                                return ModalToggle::make('Edit Table')
                                        ->modal('editTable')
                                        ->method('editTable', ['table_id' => $table->id])
                                        ->icon('pencil');
                            }),

                        TD::make('Delete Table')
                            ->render(function (Seating $table) {
                                return Button::make('Delete')
                                    ->icon('trash')
                                    ->type(Color::DANGER())
                                    ->method('deleteTable', ['id' => $table->id])
                                    ->confirm('Are you sure you want to delete this table?');
                                    
                            }),
                        ]),

                    Layout::rows([
                        Group::make([
                            Input::make('tablename')
                                ->title('Table Name')
                                ->placeholder('Table Name')
                                ->help('Enter the name of the table you would like to add.')
                        ]),
                        Group::make([
                            Button::make('Add Table')
                                ->icon('plus')
                                ->method('addTable')
                                ->type(Color::DEFAULT()),
                        ]),
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

    public function filter(Events $event){
        return redirect()->route('platform.eventStudents.list', [$event->id, 'ticketstatus' => request('ticketstatus')]);
    }

    //add the studen to the table from modal
    public function addStudentToTable(Request $request, Events $event)
    {
        //get the table id from the post request
        $table_id = $request->get('table_id');

        //get the student id from the post request
        $user_id = $request->get('user_id');

        try{

            //if the table id is not empty
            if(!empty($table_id)){

                //update the table id in the event attendees table
                EventAttendees::where('user_id', $user_id)->where('event_id', $event->id)->update([
                    'table_id' => ($table_id == 'remove') ? null : $table_id,
                ]);

                Toast::success('Student seating updated succesfully');

            }else{
                Toast::warning('Please select a table in order to add/update the student seating');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to add/update the student to the table. Error Message: ' . $e->getMessage());
        }
    }

    public function addTable(Request $request, Events $event)
    {
        //get the table name from the post request
        $tablename = $request->get('tablename');

        try{

            //if the table name is not empty
            if(!empty($tablename)){

                //add the table to the db
                Seating::create([
                    'tablename' => $tablename,
                    'event_id' => $event->id,
                ]);

                Toast::success('Table added succesfully');

            }else{
                Toast::warning('Please enter a table name in order to add the table');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to add the table. Error Message: ' . $e->getMessage());
        }
    }

    public function deleteTable(Request $request, Events $event)
    {
        //get the table id from the post request
        $id = $request->get('id');

        try{

            //if the table id is not empty
            if(!empty($id)){

                //delete the table from the db
                Seating::where('id', $id)->delete();

                Toast::success('Table deleted succesfully');

            }else{
                Toast::warning('Please select a table in order to delete it');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to delete the table. Error Message: ' . $e->getMessage());
        }
    }

    public function editTable(Request $request)
    {
        //get the table id from the post request
        $table_id = $request->get('table_id');

        try{

            //if the table id is not empty
            if(!empty($table_id)){

                //get the table from the db
                $table = Seating::where('id', $table_id)->first();

                //update the tablename
                $table->tablename = $request->get('tablename');

                //save the table
                $table->save();

                Toast::success('Table updated succesfully');


            }else{
                Toast::warning('Please select a table in order to edit it');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to edit the table. Error Message: ' . $e->getMessage());
        }
    }
}