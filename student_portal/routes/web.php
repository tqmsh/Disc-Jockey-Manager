<?php
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\BuyTicketsScreen;
use App\Orchid\Screens\ViewEventScreen;



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

Route::middleware(['cors'])->group(function () {
    Route::get('paypal/payment', [ViewEventScreen::class, 'payment'])->name('paypal');
});

Route::get('success/success/{event_id}', [ViewEventScreen::class, 'success'])->name('paypal_success');

Route::get('success/cancel', [ViewEventScreen::class, 'cancel'])->name('paypal_cancel');