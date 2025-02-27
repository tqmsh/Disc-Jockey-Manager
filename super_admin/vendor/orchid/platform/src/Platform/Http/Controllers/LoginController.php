<?php

declare(strict_types=1);

namespace Orchid\Platform\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Session;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Orchid\Access\UserSwitch;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * @var Guard|\Illuminate\Auth\SessionGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->guard = $auth->guard(config('platform.guard'));

        $this->middleware('guest', [
            'except' => [
                'logout',
                'switchLogout',
            ],
        ]);
    }

    private function setStartTimeInSession(): void {
        session(['login_start_time' => now()]);
    }
    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     *
     * @throws ValidationException
     *
     * @return JsonResponse|RedirectResponse
     */
    public function login(Request $request, CookieJar $cookieJar)
    {
        try{
            $request->validate([
                'email'    => 'required|string',
                'password' => 'required|string',
            ]);

            $auth = $this->guard->attempt(
                $request->only(['email', 'password']),
                $request->filled('remember')
            );

            if ($auth) {

                $user = User::where('email', $request->input('email'))->first();

                if(is_null($user->role) || $user->role != 1){

                    $this->guard->logout();

                    $cookieJar->forget('lockUser');

                    $request->session()->invalidate();

                    $request->session()->regenerateToken();

                    throw ValidationException::withMessages(['email' => __('The details you entered did not match our records. Please double-check and try again.'),]);
                }
                $this->setStartTimeInSession();

                return $this->sendLoginResponse($request);
            }

            throw ValidationException::withMessages([
                'email' => __('The details you entered did not match our records. Please double-check and try again.'),
            ]);
        }catch(Exception $e){
            throw ValidationException::withMessages(['email' => __('The details you entered did not match our records. Please double-check and try again.'),]);
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();        

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended(route(config('platform.index')));
    }

    /**
     * @param Request $request
     * @param Guard   $guard
     *
     * @return Factory|View
     */
    public function showLoginForm(Request $request)
    {
        $user = $request->cookie('lockUser');

        /** @var EloquentUserProvider $provider */
        $provider = $this->guard->getProvider();

        $model = $provider->createModel()->find($user);

        return view('platform::auth.login', [
            'isLockUser' => optional($model)->exists ?? false,
            'lockUser'   => $model,
        ]);
    }

    /**
     * @param CookieJar $cookieJar
     *
     * @return RedirectResponse
     */
    public function resetCookieLockMe(CookieJar $cookieJar)
    {
        $lockUser = $cookieJar->forget('lockUser');

        return redirect()->route('platform.login')->withCookie($lockUser);
    }

    /**
     * @return RedirectResponse
     */
    public function switchLogout()
    {
        UserSwitch::logout();

        return redirect()->route(config('platform.index'));
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {

        $user = $this->guard->user();

        $start_time = $request->session()->get('login_start_time');

        $logoutTime = now();

        $sessionTime = $logoutTime->diffInSeconds($start_time);

        Session::create([
            'user_id' => $user->id,
            'time' => $sessionTime,
            'role' => $user->role,
        ]);

        $user->update(['start_time' => null]);

        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
