<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {

    protected $fillable = ['name', 'password', 'type', 'api_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const ADMIN_TYPE_NORMAL = 0;
    const ADMIN_TYPE_DEVELOPER = 1;

}
