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

    public function deleteSongRequest(Request $request){
        // Validate the request data
        $validatedData = $request->validate([
            'request_id' => 'required|integer',
        ]);

        $user = $request->user();
        $songRequest = SongRequests::find($validatedData['request_id']);

        // Check if the song request exists
        if (!$songRequest) {
            return response()->json([
                'message' => 'Song request not found.'
            ], 404);
        }

        // Check if the user is the one who made the song request
        if ($songRequest->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this song request.'
            ], 403);
        }

        // Delete the song request
        $songRequest->delete();

        return response()->json([
            'message' => 'Song request deleted successfully.'
        ]);
    }

    public function requestSong(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'song_id' => 'required|string',
            'event_id' => 'required|string',
        ]);
        $user = $request->user();
        // Check if the song request already exists
        if (SongRequests::where('song_id', $validatedData['song_id'])
            ->where('event_id', $validatedData['event_id'])
            ->where('user_id', $user->id)
            ->exists()) {
            return response()->json(
                ['alreadyRequested' => true]
            , 400);
        }
        // Check if the user is part of the event
        
        if (!Songs::where('id', $validatedData['song_id'])->exists()) {
            return response()->json([
                'message' => 'Song does not exist.'
            ], 400);
        }
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

        if ($request->has('user_id')) {
            $allSongRequests = SongRequests::where('event_id', $validatedData['event_id'])
                ->where('user_id', $request->input('user_id'))
                ->join('songs', 'song_requests.song_id', '=', 'songs.id')
                ->select('song_requests.*', 'songs.title', 'songs.artists', 'songs.explicit', 'songs.status')
                ->get();
        } else {
            $allSongRequests = SongRequests::where('event_id', $validatedData['event_id'])
                ->join('songs', 'song_requests.song_id', '=', 'songs.id')
                ->select('song_requests.*', 'songs.title', 'songs.artists', 'songs.explicit', 'songs.status')
                ->get();
        }
        
        return response()->json($allSongRequests);
    }
}
