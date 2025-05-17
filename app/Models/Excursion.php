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
        'group_c_seats',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'group_a_seats' => 'integer',
        'group_b_seats' => 'integer',
        'group_c_seats' => 'integer',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function availableSeats(string $group): int
    {
        $groupKey = "group_{$group}_seats";
        if (!in_array($group, ['a', 'b', 'c'])) {
            return 0;
        }
        return $this->$groupKey ?? 0;
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

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }
}