<?php

declare(strict_types=1);

namespace Orchid\Platform\Http\Controllers;

use Exception;
use App\Models\School;
use App\Models\Student;
use App\Models\Localadmin;
use App\Models\Session as UserSession;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Cookie\CookieJar;
use Orchid\Access\UserSwitch;
use Orchid\Access\Impersonation;
use Orchid\Platform\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Notifications\GeneralNotification;
use App\Models\Specs;

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
        try {
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
    
                if ($user->role != 3 || $user->account_status == 0) {
                    $this->guard->logout();
                    $cookieJar->forget('lockUser');
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
    
                    throw ValidationException::withMessages(['email' => __('The details you entered did not match our records. Please double-check and try again.')]);
                }

                
                $this->setStartTimeInSession();

                return $this->sendLoginResponse($request);
            }

            throw ValidationException::withMessages([
                'email' => __('The details you entered did not match our records. Please double-check and try again.'),
            ]);

        } catch (Exception $e) {

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

            //$request->validate will automatically validate unique emails and other specefied validations
            $formFields = $request->validate([
                'name' => ['required'],
                'firstname' => ['required'],
                'lastname' => ['required'],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => 'required|confirmed|min:6',
                'password_confirmation' => ['required'],
                'phonenumber' => ['required'],
                'school' => ['required'],
                'country' => ['required'],
                'state_province' => ['required'],
                'county' => ['required_if:country,USA', 'prohibited_if:country,Canada'],
                'city_municipality' => ['required_if:country,Canada', 'prohibited_if:country,USA'],
                'grade' => ['required'],
                'allergies' => ['nullable'],
                'interested_prom_purchases' => ['required']
            ]);
    
            // Hash Password
            $formFields['password'] = bcrypt($formFields['password']);
            
            try{

                //check if the school the user entered is valid
                $school = School::where('school_name', $formFields['school'])
                                    ->where('state_province', $formFields['state_province'])
                                    ->where('country', $formFields['country']);
                                
                // Get school based off either county or city/municipality depending on the country field
                if ($formFields['country'] == 'USA') {
                    $school_id = $school->where('county', $formFields['county'])->get()->value('id');
                } else {
                    $school_id = $school->where('city_municipality', $formFields['city_municipality'])->get()->value('id');
                }

            }catch(Exception $e){

                Session::flash('message', 'There was an internal server error. Please contact one of the admins of Prom Planner.');
    
                return redirect('/admin/register');
            }

    
            if(is_null($school_id)){

                Session::flash('message', 'You are trying to enter a school that does not exist. Please review your, school name, county/city/municipality, country and state/province.');

                return redirect('/admin/register');

            }else{

                try{
                    $userTableFields = $request->only(['name', 'firstname', 'lastname', 'email', 'phonenumber', 'country']); 
                    $userTableFields['password'] = $formFields['password'];
                    $userTableFields['role'] = 3;

                    $user = User::create($userTableFields);
    
                    if($user){
                        
                        $studentTableFields = $request->only(['firstname', 'lastname', 'email', 'phonenumber', 'school', 'grade', 'allergies']);
                        
                        $studentTableFields['school_id'] = $school_id;
                        $studentTableFields['user_id'] = $user->id;

                        $studentCreateSuccess = Student::create($studentTableFields);

                        $specsCreateSuccess = Specs::create([
                            'student_user_id' => $user->id,
                            'gender' => $formFields['interested_prom_purchases']
                        ]);

                        if($studentCreateSuccess && $specsCreateSuccess){
                            Session::flash('message', 'Your account has been created successfully! Please wait until an admin approves your account. You will not be able to log in until then.');

                            //notify all admins that a new vendor has registered
                            $superAdmins = User::where('role', 1)->get();
                            $localAdmins = User::where('role', 2)->whereIn('id', Localadmin::where('school_id', $school_id)->get('user_id')->toArray())->get();

                            foreach($superAdmins as $admin){
                                $admin->notify(new GeneralNotification([
                                    'title' => 'New Student Registered',
                                    'message' => 'A new student has registered. Please approve or deny their account.',
                                    'action' => '/admin/pendingstudents',

                                ]));
                            }
                            
                            foreach($localAdmins as $admin){
                                $admin->notify(new GeneralNotification([
                                    'title' => 'New Student Registered',
                                    'message' => 'A new student has registered. Please approve or deny their account.',
                                    'action' => '/admin/pendingstudents',

                                ]));
                            }
                    
                            return redirect('/admin/login');  
                        }
                    }

                }catch(Exception $e){

                    Session::flash('message', 'There was an error creating your account. Please contact one of the admins of Prom Planner.' . $e);
            
                    return redirect('/admin/register');
                }
            }
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

        UserSession::create([
            'user_id' => $user->id,
            'time' => $sessionTime,
            'role' => $user->role,
        ]);

        User::find($user->id)->update(['start_time' => null]);
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
