<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'excursion_id' => 'required|exists:excursions,id',
            'group_type' => 'required|in:a,b,c',
            'number_of_people' => 'required|integer|min:1',
            'excursion_date' => 'required|date',
        ]);

        $excursion = Excursion::findOrFail($validated['excursion_id']);
        $original_price = $excursion->price * $validated['number_of_people'];

        $discount = 0;
        $discount_amount = 0;
        $final_price = $original_price;

        if ($validated['number_of_people'] >= 5) {
            $discount = 10;
            $discount_amount = $original_price * 0.10;
            $final_price = $original_price - $discount_amount;

            session([
                'show_discount' => true,
                'original_price' => $original_price,
                'discount_amount' => $discount_amount,
                'final_price' => $final_price,
            ]);
        } else {
            session()->forget(['show_discount', 'original_price', 'discount_amount', 'final_price']);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'excursion_id' => $validated['excursion_id'],
            'group_type' => $validated['group_type'],
            'number_of_people' => $validated['number_of_people'],
            'status' => 'pending',
            'booking_date' => $validated['excursion_date'],
            'discount' => $discount,
            'discount_amount' => $discount_amount,
            'final_price' => $final_price,
        ]);

        return redirect()->back()->with('success', 'Бронирование успешно создано!');
    }

    public function clearDiscountSession(Request $request)
    {
        $request->session()->forget(['show_discount', 'original_price', 'discount_amount', 'final_price']);
        return response()->json(['status' => 'success']);
    }

    // Новый метод для обновления статуса бронирования
    public function updateStatus(Request $request, Booking $booking)
    {
        // Правильная проверка на админа
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'У вас нет прав для этого действия.');
        }

        // Добавляем 'completed' в список допустимых статусов
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // Обновляем статус
        $booking->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Статус бронирования обновлен!');
    }

}