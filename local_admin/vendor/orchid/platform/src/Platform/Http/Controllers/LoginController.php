<?php

declare(strict_types=1);

namespace Orchid\Platform\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Orchid\Access\UserSwitch;
use Illuminate\Validation\Rule;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

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

                if(is_null($user->role)==true || strcmp($user->role, 'localadmin') !== 0 || is_null($user->permissions)==true){

                    $this->guard->logout();

                    $cookieJar->forget('lockUser');

                    $request->session()->invalidate();

                    $request->session()->regenerateToken();

                    throw ValidationException::withMessages(['email' => __('The details you entered did not match our records. Please double-check and try again.'),]);
                }

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

    public function showRegisterForm(Request $request){
        $user = $request->cookie('lockUser');

        /** @var EloquentUserProvider $provider */
        $provider = $this->guard->getProvider();

        $model = $provider->createModel()->find($user);

        return view('platform::auth.register', [
            'isLockUser' => optional($model)->exists ?? false,
            'lockUser'   => $model,
        ]);    
    }

    public function register(Request $request){
        $formFields = $request->validate([
            'name' => ['required'],
            'firstname' => ['required'],
            'lastname' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => ['required'],
            'phonenumber' => ['required'],
            'school' => ['required'],
            'country' => ['required'],
            'state_province' => ['required'],
            'county' => ['required'],
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);
        
        Session::flash('message', 'Your account has been created successfully! Please wait until an admin approves your account. You will not be able to log in until then.');

        return redirect('/admin/login');    
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
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
