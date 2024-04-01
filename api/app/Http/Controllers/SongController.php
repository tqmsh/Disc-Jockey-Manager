<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Localadmin;
use App\Models\Songs;
use App\Models\SongRequests;
use App\Models\EventAttendees;
use Illuminate\Http\Request;



class SongController extends Controller
{
    public function index()
    {
        $allSongs = Songs::all();

        return response()->json($allSongs);
    }

    public function show($id)
    {
        // Logic to fetch a specific song by its ID from the database
    }

    public function store(Request $request)
    {
        // Logic to store a new song in the database
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific song by its ID in the database
    }

    public function destroy($id)
    {
        // Logic to delete a specific song by its ID from the database
    }

    public function requestSong(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'song_id' => 'required|string',
            'event_id' => 'required|string',
        ]);

        // Check if the user is part of the event
        $user = $request->user();
            
        if(!EventAttendees::where('user_id', $user->id)->where('event_id', $validatedData['event_id'])->exists())
        {
            return response()->json([
                'message' => 'You are not at this event.'
            ], 400);
        }

        if ($user->role==3) {
            $songRequest = SongRequests::create([
                'song_id' => $validatedData['song_id'],
                'event_id' => $validatedData['event_id'],
                'user_id' => $user->id,
            ]);
        } else {
            return response()->json(['message' => 'Only students can request songs.']);
        }

        // Return the created song request
        return response()->json($songRequest);
    }
    
    public function getAllSongRequests(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'event_id' => 'required|string',
        ]);

        
        $allSongRequests = SongRequests::where('event_id', $validatedData['event_id'])->get();
        return response()->json($allSongRequests);
    }
}
