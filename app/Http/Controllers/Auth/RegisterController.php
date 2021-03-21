<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserCertificateImage;
use Decorate\Facades\ImageUpload;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws ValidationException
     */
    protected function create(array $data)
    {
        $user = User::query()->where('email', $data['email'])->first();

        if ($user && $user->password) {
            throw ValidationException::withMessages(['email' => 'このメールアドレスは既に登録されています']);
        }

        $data['password'] = Hash::make($data['password']);
        $data['api_token'] = Str::random(60);

        $user = User::query()->firstOrNew(['email' => $data['email']]);

        $user->fill($data)->save();

        return $user;
    }

    protected function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        event(new Registered($user));

        $this->guard()->login($user);

        return $user->makeVisible('api_token');
    }

}
