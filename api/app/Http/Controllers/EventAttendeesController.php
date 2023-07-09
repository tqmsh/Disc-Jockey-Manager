<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\EventAttendees;
use App\Models\Seating;
use Illuminate\Http\Request;
use App\Models\User;

class EventAttendeesController extends Controller
{
    public function getTable(Request $request)
    {
        $user = $request->user();
        $fields = $request->validate([
            'event_id' => 'required|string',
        ]); 
        if($user->role == 3){
            $student = Student::where('user_id', $user->id)->first();
            //Get all tables where user_id is student->user_id and event_id is event_id

            //Get all tables from eventatendees where user_id is student->user_id and event_id is event_id, but only take talbe_id
            $table_id = EventAttendees::where('user_id', $student->user_id)->where('event_id', $fields['event_id'])->get('table_id');
            $table_id = $table_id[0]->table_id;
            $tablename = Seating::where('id', $table_id)->get("tablename");
            $tablename = $tablename[0]->tablename;

            $tableAttendee = EventAttendees::where('event_id', $request->event_id)->where('table_id', $table_id)->where('approved', '1')->get(['user_id']);
            foreach($tableAttendee as $attendee){
                $user = User::where('id', $attendee->user_id)->get(['firstname', 'lastname']);
                $students[] = $user;
            }

            $response = [
                'table' => $tablename,
                'table_id' => $table_id,
                'seated' => $students,
            ];
        
            return $response;
        }else{
            return response()->json(['message' => "You're not a student"], 401);
        }
    }

    public function validateCode(Request $request){
        $user = $request->user();

        $request->validate([
            'event_id' => 'required|string',
            'ticket_code' => 'required',
        ]);

        // Make sure the call is made by localadmin
        if($user->role == 2){
            // Validate the ticket code
            $attendee = EventAttendees::where('ticket_code', $request->ticket_code)->first();

            // Check if the ticket code exists 
            if(!$attendee){
                return response()->json(['message' => "Code is not valid"]);
            }

            // Check if the ticket code is for the right event
            if(!($attendee->event_id == $request->event_id)){
                return response()->json(['message' => "Code is not valid"]);
            }else{
                // Update event_attendees checkin status (Think about if nulling the code is necessary)

            }

            // Return the event_attendees information for the local admin to see and confirm (table_id, firstname, lastname etc.)
            $user = User::where('id', $attendee->user_id)->first();

            $response = [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'table_id' => $attendee->table_id,
            ];

            return $response;

        // Reject call if made by anyone else 
        }else{
            return response()->json(['message' => "You're not a local admin"], 401);
        }
    }
}
