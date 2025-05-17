<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'excursion_id',
        'group_type',
        'number_of_people',
        'status',
        'booking_date',
        'discount',
        'discount_amount',
        'final_price',
        'name',
    ];
    protected $casts = [
        'booking_date' => 'datetime',
        'number_of_people' => 'integer'
    ];

    protected $dates = [
        'booking_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function excursion()
    {
        return $this->belongsTo(Excursion::class);
    }

    
} 

