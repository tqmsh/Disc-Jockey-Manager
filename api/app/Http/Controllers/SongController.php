<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Localadmin;
use App\Models\Songs;
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

}
