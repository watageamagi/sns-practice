<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->email,
        ]);

        $mail_check = Auth::user()->email === $request->email;
        tap($user)->update($request->only('name', 'email'));

        if(!$mail_check) {
            $user->email_verified_at = null;
            $user->save();
            event(new Registered($user));
            return response()->json(['email_update' => true]);
        }
    }
}
