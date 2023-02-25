<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Localadmin;
use App\Models\Events;
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
}