<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\EventAttendees;
use App\Models\Seating;
use Illuminate\Http\Request;

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
            
            $response = [
                'table' => $tablename,
                'table_id' => $table_id
            ];
        
            return $response;
        }else{
            return response()->json(['message' => "You're not a student"], 401);
        }
    }
}
