<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcursionPhoto extends Model
{
    protected $fillable = ['excursion_id', 'photo_path', 'is_preview'];

    public function excursion()
    {
        return $this->belongsTo(Excursion::class);
    }
}