<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class Guests extends Model implements AuthenticatableContract, AuthorizableContract
{
    use \Illuminate\Auth\Authenticatable,
        Authorizable,HasFactory;

    protected $table="guests";
    protected $guard="user";

    protected $fillable=[
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'avatar',
        'session',
        'session_expiry'
    ];

    protected $hidden=[
        'password',
        'remember_token'
    ];

    protected $casts=[
        'email_verified_at' => 'datetime',
        'session_expiry' => 'datetime',
    ];
}
