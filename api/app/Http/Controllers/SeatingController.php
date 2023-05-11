<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seating;
use App\Models\EventAttendees;
class SeatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function requestSeat(Request $request)
    {
        
        $request->validate([
            'event_id' => 'required',
            'table_id' => 'required'
        ]);
        $user = $request->user();

        //Check if user is at the event
        if(!EventAttendees::where('user_id', $user->id)->where('event_id', $request['event_id'])->exists())
        {
            return response()->json([
                'message' => 'You are not at this event.'
            ], 400);
        }

        // Getting the table 
        $table = Seating::find($request['table_id']);
        
        if(!$table){
            return response()->json([
                'message' => 'Table does not exist.'
            ], 400);
        }
        
        // Check if already seated at current table
        if(EventAttendees::where('user_id', $user->id)->where('event_id', $request['event_id'])->where('table_id', $table->id)->where('approved', '1')->exists())
        {
            return response()->json([
                'message' => 'You are already seated at this table.'
            ], 400);
        }

        // Checking if the student has requested to be seated at another table already (minimize requests, preventing abuse)
        if(EventAttendees::where('user_id', $user->id)->where('event_id', $request['event_id'])->where('approved', '0')->exists())
        {
            return response()->json([
                'message' => 'You have already requested to be seated at another table. Please wait for the host to accept your request.'
            ], 400);
        }
        
        // Check if table is full
        else if($table->capacity == 0){
            return response()->json([
                'message' => 'This table is full. Please select another table.'
            ], 400);
        }

        // Check if student has already requested to be seated at this table
        else if(EventAttendees::where('user_id', $user->id)->where('event_id', $request['event_id'])->where('table_id', $table->id)->where('approved', '0')->exists())
        {
            return response()->json([
                'message' => 'You have already been seated at a table.'
            ], 400);
        }
        else{
            EventAttendees::create([
                'user_id' => $user->id,
                'event_id' => $request['event_id'],
                'table_id' => $table->id,
                'ticketstatus' => EventAttendees::where('event_id', $request['event_id'])->where('user_id', $user->id)->whereNot('table_id', $table->id)->get('ticketstatus')->value('ticketstatus')
            ]);
            return response()->json([
                'message' => 'Request sent.',
            ], 200);
        }
    }
}
