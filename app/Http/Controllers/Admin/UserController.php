<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function users(Request $request) {
        return User::query()
            ->orderBy('created_at', 'desc')
            ->search($request)
            ->pagination($request);
    }

    public function detail($id) {
        $user = User::query()->find($id);

        return [
            'user' => $user,
        ];
    }

    public function update(Request $request) {
        $user = User::query()->find($request->id);
        $user->fill($request->all())->save();

        return $user;
    }
}
