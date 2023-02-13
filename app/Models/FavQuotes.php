<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavQuotes extends Model
{
    use HasFactory;

    protected $table="fav_quotes";

    protected $fillable =[ 
            'guest_id',
            'anime',
            'character',
            'quote'
    ];

    public function guest()
    {
        return $this->belongsTo(Guests::class, 'guest_id', 'id');
    }
}
