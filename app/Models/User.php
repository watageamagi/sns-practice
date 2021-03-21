<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyMailJP;
use App\Extensions\Relations\Pagination;
use App\Notifications\ResetPasswordJP;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContracts;

class User extends Authenticatable implements MustVerifyEmailContracts
{
    use Notifiable, Pagination, HasApiTokens, MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    protected $appends = ['is_verify'];

    /**
     * override
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMailJP);
    }
    /**
     * override
     * パスワードリセットメール日本語化
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordJP($token));
    }

    public function getIsVerifyAttribute() {
        return $this->hasVerifiedEmail();
    }

    /**
     * @param Builder $query
     * @param Request     $request
     *
     * @return Builder
     */
    public function scopeSearch (Builder $query, Request $request) {
        if ($request->query('name', false)) {
            $query->where('name', 'LIKE', "%{$request->query('name')}%");
        }

        return $query;
    }
}
