<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return view('welcome');
});

Route::get('/download-csv', function () {
    $filePath = public_path('image/sample.csv'); // Replace with the actual path to your CSV file

    $headers = [
        'Content-Type' => 'text/csv',
        'Refresh' => '0',
    ];
    return Response::download($filePath, 'sample.csv', $headers);
    
})->name('csv');
