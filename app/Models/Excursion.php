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

    // Связь с гидом
    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    // Связь с отзывами
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Отзывы, одобренные администратором
    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true);
    }

    // Связь с бронированиями
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Связь с фотографиями
    public function photos()
    {
        return $this->hasMany(ExcursionPhoto::class);
    }

    // Метод для получения главного фото (превью)
    public function previewPhoto()
    {
        return $this->photos()->where('is_preview', true)->first();
    }

    // Расчет доступных мест
    public function availableSeats(string $group, ?string $date = null): int
    {
        $groupKey = "group_{$group}_seats";
        if (!in_array($group, ['a', 'b', 'c'])) {
            return 0;
        }

        // Получаем общее количество мест
        $totalSeats = $this->$groupKey ?? 0;

        // Если дата не указана, возвращаем общее количество мест
        if (!$date) {
            return $totalSeats;
        }

        // Получаем количество забронированных мест на указанную дату
        $bookedSeats = $this->bookings()
            ->where('group_type', $group)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->sum('number_of_people');

        // Возвращаем количество свободных мест
        return $totalSeats - $bookedSeats;
    }

    // Определение сезона
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

    // Связь с рейтингами (если используется)
    public function ratings()
    {
        return $this->hasMany(ExcursionRating::class);
    }

    // Средний рейтинг
    public function getAverageRatingAttribute()
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->avg('rating') ?? 0;
    }

    // Общее количество бронирований
    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }

    // Скоуп для активных экскурсий
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Скоуп для предстоящих экскурсий
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    // Скоуп для прошедших экскурсий
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }
}