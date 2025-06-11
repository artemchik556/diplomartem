<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = User::findOrFail(auth()->id());
        $bookings = $user->bookings()->with('excursion');

        // Фильтр по статусу
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $bookings->where('status', 'completed');
            } else {
                $bookings->where('status', $request->status);
            }
        }

        // Сортировка по дате
        if ($request->filled('sort_date')) {
            $bookings->orderBy('booking_date', $request->sort_date);
        }

        // Сортировка по названию экскурсии
        if ($request->filled('sort_title')) {
            $bookings->join('excursions', 'bookings.excursion_id', '=', 'excursions.id')
                    ->orderBy('excursions.title', $request->sort_title)
                    ->select('bookings.*');
        }

        $bookings = $bookings->get();

        // Логирование для отладки
        Log::info('Profile bookings filtered', [
            'user_id' => $user->id,
            'status' => $request->status,
            'sort_date' => $request->sort_date,
            'sort_title' => $request->sort_title,
            'bookings_count' => $bookings->count(),
            'bookings' => $bookings->toArray(),
        ]);

        if ($request->ajax()) {
            return response()->json(['bookings' => $bookings]);
        }

        return view('profile.show', compact('user', 'bookings'));
    }
}
