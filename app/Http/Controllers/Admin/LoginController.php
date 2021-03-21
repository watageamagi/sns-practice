<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    public function __construct(Request $request)
    {
        $this->middleware('guest:admin')->except('logout');;
    }

    public function username (){
        return 'name';
    }

    protected function loggedOut(Request $request)
    {
        return redirect('/admin/login');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->update(['api_token' => Str::random(60)]);
        return $user->makeVisible('api_token');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->update(['api_token' => null]);

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/admin');
    }

    protected function guard()
    {
        return Auth::guard('web-admin');
    }
}
