<?php
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\BuyCreditsScreen;
use App\Models\Vendors;

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

Route::post('paypal/payment', [BuyCreditsScreen::class, 'payment'])->name('paypal');

Route::get('success/success/{price}/{credits}', [BuyCreditsScreen::class, 'success'])->name('paypal_success');

Route::get('success/cancel', [BuyCreditsScreen::class, 'cancel'])->name('paypal_cancel');

Route::post('/user/tab-closed', [LoginController::class, 'logout']);

Route::get('/disable-ad', function() {
    return view('ad_blocker_blocker');
});