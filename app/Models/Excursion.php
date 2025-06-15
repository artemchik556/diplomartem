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
        'transport_car',
        'transport_bus',
        'transport_train',
        'preparation_level',
        'guide_a_id',
        'guide_b_id',
        'guide_c_id',
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

    // Связь с гидом для группы A
    public function guideA()
    {
        return $this->belongsTo(Guide::class, 'guide_a_id');
    }

    // Связь с гидом для группы B
    public function guideB()
    {
        return $this->belongsTo(Guide::class, 'guide_b_id');
    }

    // Связь с гидом для группы C
    public function guideC()
    {
        return $this->belongsTo(Guide::class, 'guide_c_id');
    }

    // Остальные методы остаются без изменений
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

    public function photos()
    {
        return $this->hasMany(ExcursionPhoto::class);
    }

    public function previewPhoto()
    {
        return $this->photos()->where('is_preview', true)->first();
    }

    public function availableSeats(string $group, ?string $date = null): int
    {
        $groupKey = "group_{$group}_seats";
        if (!in_array($group, ['a', 'b', 'c'])) {
            return 0;
        }

        $totalSeats = $this->$groupKey ?? 0;

        if (!$date) {
            return $totalSeats;
        }

        $bookedSeats = $this->bookings()
            ->where('group_type', $group)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->sum('number_of_people');

        return $totalSeats - $bookedSeats;
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

    public function ratings()
    {
        return $this->hasMany(ExcursionRating::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->avg('rating') ?? 0;
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