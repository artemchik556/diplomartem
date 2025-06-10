<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcursionRating extends Model
{
    protected $fillable = [
        'excursion_id',
        'user_id',
        'rating',
        'review'
    ];

    public function excursion()
    {
        return $this->belongsTo(Excursion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 