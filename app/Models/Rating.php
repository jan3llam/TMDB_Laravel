<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table="ratings";

    protected $fillable =[ 
            'guest_id',
            'media_type',
            'show_id',
            'value'
    ]

    public function guest()
    {
        return $this->belongsTo(Guests::class, 'guest_id', 'id');
    }
}
