<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Student;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\DropDown;
use App\Orchid\Layouts\ViewStudentLayout;
use App\Orchid\Layouts\ViewUnattendingStudentLayout;

class ViewEventStudentScreen extends Screen
{
    public $event;
    public $students;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event,
            'students' => Student::whereIn('user_id', EventAttendees::where('event_id', $event->id)->get(['user_id']))->paginate(20),
            'unattending_students' => Student::whereNotIn('user_id', EventAttendees::where('event_id', $event->id)->get(['user_id']))->paginate(20)
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
            Layout::tabs([
                'Attending Students' => [
                    ViewStudentLayout::class
                ],

                'Add Students' => [
                    ViewUnattendingStudentLayout::class
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
}
