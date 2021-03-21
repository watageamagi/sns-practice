<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('app.index');
    }

    protected function loggedOut(Request $request)
    {
         return redirect('/login');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->update(['api_token' => Str::random(60)]);
        return $user->makeVisible('api_token');
    }

    public function logout(Request $request)
    {

        Auth::guard('web')->logout();

//        $request->session()->invalidate();
//        $request->session()->regenerateToken();

        return response()->json([true]);
    }
}
