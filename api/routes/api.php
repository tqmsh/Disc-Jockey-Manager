<?php

use App\Models\School;
use App\Models\User;
use App\Models\Events;
use App\Models\StudentBids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StudentBidsController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\LocaladminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EventAttendeesController;
use App\Http\Controllers\PromVoteController;
use App\Http\Controllers\SeatingController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Routes

// Login Route
Route::post('/login', [AuthController::class, 'login']);

// Register Route
Route::post('/register/student', [RegisterController::class, 'register']);

// Impression counter
Route::put("campaign_view/{id}", [AdController::class, "impression"]);

// Click counter
Route::put("campaign_click/{id}", [AdController::class, "click"]);

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'OK'], 200);
});

// Get all schools
Route::get('getAllSchools', [SchoolController::class, 'getAllSchools']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Student Routes
    Route::patch('/updateStudent', [StudentController::class, 'updateStudent']);
    
    // Additional Event Methods
    Route::get('/getEvents', [EventController::class, 'getEvents']);

    Route::get('/getAllTables', [EventController::class, 'getTables']);

    // Events Routes
    // Route::resource('/events', EventController::class);

    // Request Seating
    Route::post('/requestTable', [SeatingController::class, 'requestSeat']);
    
    // Additional StudentBid Methods
    Route::get('/getStudentBids', [StudentBidsController::class, 'getStudentBids']);

    Route::get('/getTable', [EventAttendeesController::class, 'getTable']);

    Route::get('/validateCode', [EventAttendeesController::class, 'validateCode']);

    Route::get('/tokenIsValid', [AuthController::class, 'tokenIsValid']);

    Route::patch('/createCode', [EventAttendeesController::class, 'createCode']);

    Route::get('/songs', [SongController::class, 'index']);

    Route::get('/songRequests', [SongController::class, 'getAllSongRequests']);

    Route::post('/requestSong', [SongController::class, 'requestSong']);

    Route::delete('/deleteSongRequest', [SongController::class, 'deleteSongRequest']);

    // PromVote data
    Route::get('/voteData/{event_id}/{user_id}', [PromVoteController::class, 'getVoteData']);
});
