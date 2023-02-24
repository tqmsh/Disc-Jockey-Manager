<?php

namespace App\Http\Controllers;

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
        // Return the events that have the same school_id of logged in user
        $user = $request->user();

        if($user->school_id == null){
            return response()->json(['message' => 'School not found'], 404);
        }else if($user->role == 1){
            return Events::all();
        }else if($user->role ==2 || $user->role == 3){
            $events = Events::where('school_id', $user->school_id)->get();
            return $events;
        }else {
            return response()->json(['message' => 'You are not authorized to view events'], 404);
        }
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
    }}
