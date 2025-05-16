<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Excursion;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Excursion $excursion)
    {
        // Check if user has already reviewed this excursion
        $existingReview = Review::where('user_id', Auth::id())
            ->where('excursion_id', $excursion->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Вы уже оставили отзыв на эту экскурсию.');
        }

        // Check if user has booked this excursion
        $hasBooked = Booking::where('user_id', Auth::id())
            ->where('excursion_id', $excursion->id)
            ->exists();

        if (!$hasBooked) {
            return back()->with('error', 'Вы можете оставить отзыв только после бронирования экскурсии.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $review = new Review([
            'user_id' => Auth::id(),
            'excursion_id' => $excursion->id,
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
            'is_approved' => false
        ]);

        $review->save();

        return back()->with('success', 'Отзыв успешно отправлен и будет опубликован после проверки администратором.');
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Отзыв успешно одобрен.');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Отзыв отклонен и удален.');
    }

    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        if ($validated['status'] === 'rejected') {
            $review->delete();
            return back()->with('success', 'Отзыв отклонен и удален.');
        }

        $review->update([
            'is_approved' => $validated['status'] === 'approved'
        ]);

        return back()->with('success', 'Статус отзыва успешно обновлен.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Отзыв успешно удален.');
    }
} 