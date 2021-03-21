<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => ['required','confirmed', 'min:8'],
        ],[
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);
    }
}
