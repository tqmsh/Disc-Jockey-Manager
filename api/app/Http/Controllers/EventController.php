<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Localadmin;
use App\Models\Events;
use App\Models\EventAttendees;
use App\Models\Seating;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getEvents(Request $request){

        // Return the events that have the same school_id of logged in user
        $user = $request->user();
        if($user->role == 1){
            return Events::all();
        }else if($user->role == 2){

            // Check if the local admin is assigned to a school
            $localadmin = Localadmin::where('user_id', $user->id)->first();
            if(!$localadmin->school_id){
                return response()->json(['message' => 'You are not assigned to a school'], 404);
            }else{
                $events = Events::where('school_id', $localadmin->school_id)->get();
                return $events;
            }

           
        }else if($user->role == 3){
            $student = Student::where('user_id', $user->id)->first();
            if(!$student->school_id){
                return response()->json(['message' => 'You are not assigned to a school'], 404);
            }else{
            $events = Events::where('school_id', $student->school_id)->get();
            return $events;
            }
        }else{
            return response()->json(['message' => 'You are not authorized to view events'], 404);
        }
    }

    public function getTables(Request $request){
        $user = $request->user();
        //make sure there is an event id in the request
        if(!$request->event_id){
            return response()->json(['message' => 'Event ID not found'], 404);
        }
        if($user->role == 3){
            // check if there exists an eventattendee that has bost the event_id and the user_id of the user
            $eventattendee = EventAttendees::where('event_id', $request->event_id)->where('user_id', $user->id)->first();
            if(!$eventattendee){
                return response()->json(['message' => 'You are not a part of this event'], 404);
            }else{
                $tables = Seating::where('event_id', $request->event_id)->get();
                //add a field to $tables that incluhdes all the students that are seated at that table 
                foreach($tables as $table){
                    // get all event attendees where event id is event id and table id is table id and only their firstname and lastname
                    $eventattendee = EventAttendees::where('event_id', $request->event_id)->where('table_id', $table->id)->get(['user_id']);
                    foreach($eventattendee as $eventattendee){
                        $user = User::where('id', $eventattendee->user_id)->get(['firstname', 'lastname']);
                        $students[] = $user;
                    }
                    $table["seated"] = $students;
                }
                return $tables;
            }
        }else if($user->role == 1){
            $tables = SeatingLLwhere('event_id', $request->event_id)->get();
            return $tables;
        }else{
            return response()->json(['message' => 'You are not authorized to view tables'], 404);
        }
    }
}