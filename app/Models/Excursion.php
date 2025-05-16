<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Excursion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'start_date',
        'end_date',
        'location',
        'image',
        'detail_image',
        'transport_car',
        'transport_bus',
        'transport_train',
        'preparation_level',
        'guide_id',
        'group_a_seats',
        'group_b_seats',
        'group_c_seats'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'group_a_seats' => 'integer',
        'group_b_seats' => 'integer',
        'group_c_seats' => 'integer',
    ];

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true);
    }

    public function availableSeats(string $group): int
    {
        return $this->{"group_{$group}_seats"} ?? 0;
    }

        public function getSeasonAttribute()
    {
        $month = $this->start_date->month;
        
        if (in_array($month, [12, 1, 2])) {
            return 'winter';
        } elseif (in_array($month, [3, 4, 5])) {
            return 'spring';
        } elseif (in_array($month, [6, 7, 8])) {
            return 'summer';
        } else {
            return 'autumn';
        }
    }
}