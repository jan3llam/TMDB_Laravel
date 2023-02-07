<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guests;

class UserVerify extends Model
{
    use HasFactory;

    protected $table="user_verifies";

    protected $fillable=[
        'token',
        'guest_id'
    ];


    public function guest()
    {
        return $this->belongsTo(Guests::class, 'guest_id', 'id');
    }

}
