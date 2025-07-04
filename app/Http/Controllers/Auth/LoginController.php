<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }
    

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'pseudo';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $remember = $request->filled('remember');

        // Vérification du statut Actif/Inactif
        $user = $this->guard()->getProvider()->retrieveByCredentials($credentials);
        
        if ($user && $user->status !== 'Actif') {
            return false;
        }

        return $this->guard()->attempt($credentials, $remember);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->getProvider()->retrieveByCredentials([
            'pseudo' => $request->pseudo
        ]);

        if ($user && $user->status !== 'Actif') {
            return redirect()->back()
                ->withInput($request->only('pseudo', 'remember'))
                ->withErrors([
                    'pseudo' => 'Votre compte est désactivé.',
                ]);
        }

        return redirect()->back()
            ->withInput($request->only('pseudo', 'remember'))
            ->withErrors([
                'pseudo' => __('auth.failed'),
            ]);
    }

    public function showLoginForm()
    {
        return view('login');
    }
}