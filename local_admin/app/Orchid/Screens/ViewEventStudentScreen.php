<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
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
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\ViewStudentLayout;
use App\Notifications\GeneralNotification;
use App\Orchid\Layouts\ViewUnattendingStudentLayout;
use App\Orchid\Layouts\ViewUnattendingStudentInviteLayout;

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
    public function query(Request $request, Events $event): iterable
    {
        $filters = $request->get('filter') ?? [];
        return [
            'event' => $event,
            'students' => Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->get(['user_id']))->filter($filters)->paginate(20),
            'unattending_students' => Student::whereNotIn('user_id', EventAttendees::where('event_id', $event->id)->where('invitation_status', 1)->get(['user_id']))->where('school_id', $event->school_id)->filter($filters)->paginate(20),
            'seatedStudents' =>  Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->where('table_approved', 1)->whereNotNull('table_id')->get(['user_id']))->filter($filters)->paginate(20),
            'unseatedStudents' =>  Student::whereIn('students.user_id', EventAttendees::where('event_id', $event->id)->whereNull('table_id')->get(['user_id']))->where('school_id', $event->school_id)->filter($filters)->paginate(20),
            'tables' => Seating::where('event_id', $event->id)->paginate(10),
            'table_proposals' => EventAttendees::where('event_id', $event->id)->where('table_approved', 0)->paginate(20),
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

                    Button::make('Manually Add Selected Students')
                        ->method('addStudents')
                        ->confirm('Are you sure you want to add the selected students to: ' . $this->event->event_name . '?')
                        ->icon('plus'),
                    
                    Button::make('Invite Selected Students')
                        ->method('inviteStudents')
                        ->confirm('Are you sure you want to invite the selected students to: ' . $this->event->event_name . '? Duplicate invitations will be ignored.')
                        ->icon('envelope'),

                    Button::make('Remove Selected Students')
                        ->method('deleteStudents')
                        ->confirm('Are you sure you want to remove the selected students from: ' . $this->event->event_name . '?')
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
                                $tables = $this->tables->where('capacity', '>', 0)->pluck('tablename', 'id');
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
                            ->placeholder('Table Name'),

                        Input::make('capacity')
                            ->title('Updated Table Capacity')
                            ->type('number')
                            ->placeholder('Table Capacity')
                    ]),
                ]),
            ])
            ->title('Edit table')            
            ->applyButton('Update Table')
            ->withoutCloseButton(),

            Layout::rows([
                Group::make([
                    Input::make('filter.name')
                        ->title('Name')
                        ->value(request()->get('filter')['name'] ?? '')
                        ->placeholder('Filter by name'),
                    Input::make('filter.email')
                        ->title('Email')
                        ->value(request()->get('filter')['email'] ?? '')
                        ->placeholder('Filter by email'),
                    Input::make('filter.grade')
                        ->title('Grade')
                        ->value(request()->get('filter')['grade'] ?? '')
                        ->placeholder('Filter by grade'),
                    Select::make('filter.ticketstatus')
                        ->title('Ticket Status')
                        ->empty('Ticket Status')
                        ->options([
                            'Paid' => 'Paid',
                            'Unpaid' => 'Unpaid'
                        ])
                        ->value(request()->get('filter')['ticketstatus'] ?? ''),
                ]),
                Group::make([
                    Button::make('Filter')
                        ->method('applyFilters')
                        ->icon('filter'),
                    Button::make('Clear Filters')
                        ->method('clearFilters')
                        ->icon('close'),
                ])
            ]),

            Layout::tabs([
                'Attending Students' => [
                    ViewStudentLayout::class
                ],

                'Invite Students' => [
                    ViewUnattendingStudentInviteLayout::class
                ],

                'Manually Add Students' => [
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
                                    $table = Seating::find(EventAttendees::where('user_id', $student->user_id)->where('event_id', $this->event->id)->where('table_approved', '1')->pluck('table_id'))->value('tablename');
                                    return e($table);
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

                        TD::make('Capacity')
                            ->render(function (Seating $table) {
                                return e($table->capacity);
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
                                ->help('Enter the name of the table you would like to add.'),

                            Input::make('capacity')
                                ->title('Table Capacity')
                                ->type('number')
                                ->placeholder('Table Capacity')
                                ->help('Enter the capacity of the table you would like to add.')
                        ]),
                        Group::make([
                            Button::make('Add Table')
                                ->icon('plus')
                                ->method('addTable')
                                ->type(Color::DEFAULT()),
                        ]),
                    ])

                ],

                'Table Change Requests' => [
                    Layout::table('table_proposals', [

                        TD::make('Requested Table')
                            ->render(function (EventAttendees $proposal) {
                                return e(Seating::find($proposal->table_id)->tablename);
                            }),

                        TD::make('Current Table')
                            ->render(function (EventAttendees $proposal) {
                                return e(Seating::find(EventAttendees::where('user_id', $proposal->user_id)->where('event_id', $this->event->id)->where('table_approved', '1')->pluck('table_id'))->value('tablename'));
                            }),

                        TD::make('Requested Table Capacity')
                            ->render(function (EventAttendees $proposal) {
                                return e(Seating::find($proposal->table_id)->capacity);;
                            }),

                        TD::make('Requester')
                            ->render(function (EventAttendees $proposal) {
                                $student = Student::where('user_id',$proposal->user_id)->get(['firstname','lastname']);
                                return e($student[0]->firstname . ' ' . $student[0]->lastname);
                            }),
                            
                        TD::make('Accept')
                            ->render(function (EventAttendees $proposal) {
                                return Button::make('Accept')
                                    ->icon('plus')
                                    ->method('handleTableChange', ['user_id' => $proposal->user_id, 'requested_table_id' => $proposal->table_id, 'status' => 'accepted'])
                                    ->type(Color::SUCCESS());
                            }),

                        TD::make('Decline')
                            ->render(function (EventAttendees $proposal) {
                                return Button::make('Decline')
                                    ->icon('close')
                                    ->method('handleTableChange', ['user_id' => $proposal->user_id, 'requested_table_id' => $proposal->table_id, 'status' => 'declined'])
                                    ->type(Color::DANGER());
                            }),


                    ])
                ]  
            ]),
        ];

    }

    public function applyFilters(Request $request, Events $event)
    {
        $filterParams = $request->only(['filter.name', 'filter.email', 'filter.grade']);
        return redirect()->route('platform.eventStudents.list', ['event_id' => $event->id, 'filter' => $filterParams['filter']]);
    }

    public function clearFilters(Events $event)
    {
        return redirect()->route('platform.eventStudents.list', ['event_id' => $event->id]);
    }

    public function inviteStudents(Request $request, Events $event)
    {
        //get all students from post request
        $students = $request->get('unattendingStudentsInvite');

        try{

            //if the array is not empty
            if(!empty($students)){

                //loop through the students and add them to db
                foreach($students as $student){
                    EventAttendees::firstOrCreate([
                        'user_id' => $student,
                        'inviter_user_id' => Auth::id(),
                        'event_id' => $event->id,
                        'table_approved' => 1,
                        'invited' => true
                    ]);

                    $user = User::find($student);

                    $user->notify(new GeneralNotification([
                        'title' => 'You have been invited to an event',
                        'message' => 'You have been invited to the event by the Prom Committee. Please check the event page for more details.',
                        'action' => '/admin/events',
                    ]));
                }

                Toast::success('Selected students invited succesfully');

            }else{
                Toast::warning('Please select students in order to invite them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to invite the selected students. Error Message: ' . $e->getMessage());
        }
    }

    public function handleTableChange(Request $request, Events $event)
    {
        $user_id = $request->get('user_id');
        $requested_table_id = $request->get('requested_table_id');
        $status = $request->get('status');

        if($status == 'accepted'){

            $requested_table = Seating::find($requested_table_id);

            if($requested_table->capacity == 0){
                Toast::error('Table is full');
                return;

            } else{
                
                $old_entry = EventAttendees::where('user_id', $user_id)->where('event_id', $event->id)->whereNot('table_id', $requested_table_id)->first();
                Seating::find($old_entry->table_id)->increment('capacity');
                $old_entry->delete();
                EventAttendees::where('user_id', $user_id)->where('table_id', $requested_table_id)->update(['table_approved' => 1]);
                $requested_table->decrement('capacity');

                Toast::success('Table change request accepted');
            }

        }else{
            EventAttendees::where('user_id', $user_id)->where('table_id', $requested_table_id)->delete();
            Toast::success('Table change request declined');
        }
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
        $numberOfStudents = EventAttendees::where('event_id', $event->id)->count();
        //get all students from post request
        $students = $request->get('unattendingStudents');

        if (($event->capacity) == NULL || $event->capacity > $numberOfStudents) {

            try{

                //if the array is not empty
                if(!empty($students)){

                    //loop through the students and add them to db
                    foreach($students as $student){
                        EventAttendees::create([
                            'user_id' => $student,
                            'inviter_user_id' => Auth::id(),
                            'event_id' => $event->id,
                            'invitation_status' => 1, //this is to make sure that the student is not invited again
                            'table_approved' => 1,
                        ]);

                        $user = User::find($student);

                        $user->notify(new GeneralNotification([
                            'title' => 'You have been added to an event',
                            'message' => 'You have been added to the event ' . $event->title . ' by ' . Auth::user()->firstname . ' ' . Auth::user()->lastname . '. Please check the event page for more details.',
                            'action' => '/admin/events',
                        ]));
                    }

                    Toast::success('Selected students added succesfully');

                }else{
                    Toast::warning('Please select students in order to add them');
                }

            }catch(Exception $e){
                Alert::error('There was a error trying to add the selected students. Error Message: ' . $e->getMessage());
            }
        } else {
            Alert::error("This Event has reached it's maximum capacity");
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

        $current_table_id = EventAttendees::where('user_id', $user_id)->where('event_id', $event->id)->where('table_approved', 1)->pluck('table_id')->first();

        try{

            //if the table id is not empty
            if(!empty($table_id) && $table_id != $current_table_id){

                if($table_id == 'remove'){
                    
                    $attendee = EventAttendees::where('user_id', $user_id)->where('event_id', $event->id)->get();
                    Seating::find($attendee[0]->table_id)->increment('capacity');
                    $attendee[0]->table_id = null;
                    $attendee[0]->save();

                    Toast::success('Student seating removed succesfully');
                } else{        

                    //update the student seating in the db
                    EventAttendees::where('user_id', $user_id)->where('event_id', $event->id)->update([
                        'table_id' => $table_id,
                    ]);

                    //decrement the capacity of the new table
                    Seating::find($table_id)->decrement('capacity');

                    //increment the capacity of the old table
                    if($current_table_id != null){
                        Seating::find($current_table_id)->increment('capacity');
                    }


                    Toast::success('Student seating updated succesfully');
                }

            }else{
                Toast::warning('Please select a valid table in order to add/update the student seating');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to add/update the student to the table. Error Message: ' . $e->getMessage());
        }
    }

    public function addTable(Request $request, Events $event)
    {
        //get the table name from the post request
        $tablename = $request->get('tablename');
        $capacity = $request->get('capacity');

        try{

            //if the table name is not empty
            if(!empty($tablename) && $capacity > 0){

                //add the table to the db
                Seating::create([
                    'tablename' => $tablename,
                    'capacity' => $capacity,
                    'event_id' => $event->id,
                ]);

                Toast::success('Table added succesfully');

            }else{
                Toast::warning('Please enter a table name and seat capacity in order to add the table');
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
                Seating::find($id)->delete();

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
                $table = Seating::find($table_id);

                $table->tablename = ($request->get('tablename') == null) ? $table->tablename : $request->get('tablename');
                $table->capacity = ($request->get('capacity') == null || $request->get('capacity') < 0) ? $table->capacity : $request->get('capacity');

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
