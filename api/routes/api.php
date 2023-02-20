<?php

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

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
Route::post('/register', [AuthController::class, 'register']);


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Student Routes
    Route::resource('/students', StudentController::class);
});