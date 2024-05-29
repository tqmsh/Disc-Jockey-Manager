<?php
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\BuyTicketsScreen;
use App\Orchid\Screens\ViewEventScreen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware(['cors'])->group(function () {
Route::get('paypal/payment/{event_id}', [ViewEventScreen::class, 'payment'])->name('paypal');
// });

Route::get('success/success/{event_id}', [ViewEventScreen::class, 'success'])->name('paypal_success');

Route::get('success/cancel', [ViewEventScreen::class, 'cancel'])->name('paypal_cancel');

Route::post('/user/tab-closed', [LoginController::class, 'logout']);

Route::get('/disable-ad', function() {
    return view('ad_blocker_blocker');
});

Route::get('/login-as/{key}', function(Request $request, string $key) {
    $query = \App\Models\LoginAs::where('la_key', $key)->where('portal', 3);

    if($query->exists()) {
        // Log out user before signing in as another user
        if(Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Login into target user
        Auth::loginUsingId($query->first()->user_id);
        $request->session()->regenerate();

        $query->delete();

        return redirect('/admin/dashboard');
    } else {
        // Show "Not Found" screen.
        abort(404);
    }
});
