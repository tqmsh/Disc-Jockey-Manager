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

    public function createCode(Request $request){
        $user = $request->user();

        if($user->role != 2){
            return response()->json(['message' => 'You are not authorized to use this API']);
        }
        $request->validate([
            'attendee_id' => 'required|string',
        ]);

        // Parsing event attendee
        $event_attendee = EventAttendees::where('id', $request->attendee_id)->first();

        // Checking if event attendee exists
        if(!$event_attendee){
            return response()->json(['message' => 'Event_attendee does not exist']);
        }

        // Generate a unique 15 digit code from ticket_code in eventattendees db 
        $code = rand(100000000000000, 999999999999999);
        $check = EventAttendees::where('ticket_code', $code)->first();
        
        // Make sure code is not duplicated in db, keep regenerating until done (should really optimize this, especially as the db fills up with codes)
        // Maybe it would not be a bad idea to periodically cleanse the already used codes, and sweep eventAtendees etc. 
        while($check){
            $code = rand(100000000000000, 999999999999999);
            $check = EventAttendees::where('ticket_code', $code)->first();
        }
        
        // Update event_attendee ticket_code in db
        $event_attendee->ticket_code = $code;
        $event_attendee->update();

        return response()->json(['message' => 'Code has been generated.']);
    }
}
