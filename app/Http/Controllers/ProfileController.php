<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with(['bookings' => function($query) {
            $query->orderBy('booking_date', 'desc');
        }, 'bookings.excursion', 'reviews.excursion'])
        ->findOrFail(Auth::id());
        
        return view('profile', compact('user'));
    }
}