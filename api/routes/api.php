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
use App\Http\Controllers\LocaladminController;
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
Route::post('/students/register', [StudentController::class, 'register']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Student Routes
    Route::resource('/students', StudentController::class);

    // Additional Event Methods
    Route::get('/getEvents', [EventController::class, 'getEvents']);

    // Events Routes
    // Route::resource('/events', EventController::class);

    // Additional StudentBid Methods
    Route::get('/getStudentBids', [StudentBidsController::class, 'getStudentBids']);

});