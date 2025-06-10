<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Excursion;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use App\Models\Guide;
use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExcursionController extends Controller
{
    public function index(Request $request)
    {
        $query = Excursion::query()->with(['reviews' => function($q) {
            $q->where('is_approved', true)
              ->whereNotNull('rating')
              ->where('rating', '>', 0);
        }]);

        // Поиск по названию
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Фильтрация по цене
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Фильтрация по направлению
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Фильтрация по сезону через месяц даты
        if ($request->filled('season')) {
            $season = $request->season;
            $query->where(function($q) use ($season) {
                switch ($season) {
                    case 'winter':
                        $q->whereIn(\DB::raw('MONTH(start_date)'), [12, 1, 2]);
                        break;
                    case 'spring':
                        $q->whereIn(\DB::raw('MONTH(start_date)'), [3, 4, 5]);
                        break;
                    case 'summer':
                        $q->whereIn(\DB::raw('MONTH(start_date)'), [6, 7, 8]);
                        break;
                    case 'autumn':
                        $q->whereIn(\DB::raw('MONTH(start_date)'), [9, 10, 11]);
                        break;
                }
            });
        }

        // Сортировка
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating_desc':
                    $query->withAvg(['reviews' => function($q) {
                        $q->where('is_approved', true)
                          ->whereNotNull('rating')
                          ->where('rating', '>', 0);
                    }], 'rating')->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'rating_asc':
                    $query->withAvg(['reviews' => function($q) {
                        $q->where('is_approved', true)
                          ->whereNotNull('rating')
                          ->where('rating', '>', 0);
                    }], 'rating')->orderBy('reviews_avg_rating', 'asc');
                    break;
                default:
                    $query->orderBy('price', 'asc');
            }
        }

        $excursions = $query->paginate(9);

        if ($request->ajax()) {
            $html = view('partials.excursion-cards', compact('excursions'))->render();
            return response()->json(['html' => $html, 'hasMore' => $excursions->hasMorePages()]);
        }

        return view('excursion', compact('excursions'));
    }

    public function create()
    {
        $guides = Guide::all();
        return view('admin.excursions.create', compact('guides'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:191',
                'description' => 'required|string',
                'start_date' => 'required|date_format:Y-m-d\TH:i',
                'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
                'price' => 'required|numeric|min:0',
                'preparation_level' => 'required|in:easy,medium,hard',
                'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Множественная загрузка
                'group_a_seats' => 'required|integer|min:1',
                'group_b_seats' => 'required|integer|min:1',
                'group_c_seats' => 'required|integer|min:1',
                'guide_id' => 'required|exists:guides,id',
                'location' => 'required|string|max:191',
                'transport_car' => 'nullable|string',
                'transport_bus' => 'nullable|string',
                'transport_train' => 'nullable|string',
            ]);

            $validated['start_date'] = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $validated['end_date'] = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

            $excursion = Excursion::create($validated);

            // Сохранение фотографий
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('excursions', 'public');
                    $excursion->photos()->create([
                        'photo_path' => $path,
                        'is_preview' => $index === 0, // Первое фото становится превью
                    ]);
                }
            }

            return redirect()->route('admin.excursions.index')
                ->with('success', 'Экскурсия успешно добавлена');
        } catch (\Exception $e) {
            Log::error('Error creating excursion: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Произошла ошибка при создании экскурсии. Пожалуйста, попробуйте позже.');
        }
    }

    public function show($id)
    {
        $excursion = Excursion::with('guide')->findOrFail($id);
        return view('excursion_detail', compact('excursion'));
    }

    public function edit(Excursion $excursion)
    {
        $guides = Guide::all();
        return view('admin.excursions.edit', compact('excursion', 'guides'));
    }

    public function update(Request $request, Excursion $excursion)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:191',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'start_date' => 'required|date_format:Y-m-d\TH:i',
                'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
                'location' => 'required|string|max:191',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Множественная загрузка
                'remove_photos' => 'array', // Для удаления существующих фото
                'transport_car' => 'nullable|string',
                'transport_bus' => 'nullable|string',
                'transport_train' => 'nullable|string',
                'preparation_level' => 'required|in:easy,medium,hard',
                'guide_id' => 'required|exists:guides,id',
                'group_a_seats' => 'required|integer|min:1',
                'group_b_seats' => 'required|integer|min:1',
                'group_c_seats' => 'required|integer|min:1',
            ]);

            $validated['start_date'] = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $validated['end_date'] = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

            $excursion->update($validated);

            // Удаление выбранных фотографий
            if ($request->has('remove_photos')) {
                foreach ($request->remove_photos as $photoId) {
                    $photo = $excursion->photos()->find($photoId);
                    if ($photo) {
                        Storage::disk('public')->delete($photo->photo_path);
                        $photo->delete();
                    }
                }
            }

            // Добавление новых фотографий
            if ($request->hasFile('photos')) {
                $hasPreview = $excursion->photos()->where('is_preview', true)->exists();
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('excursions', 'public');
                    $excursion->photos()->create([
                        'photo_path' => $path,
                        'is_preview' => !$hasPreview && $index === 0, // Устанавливаем превью, если его нет
                    ]);
                }
            }

            return redirect()->route('admin.excursions.index')
                ->with('success', 'Экскурсия успешно обновлена');
        } catch (\Exception $e) {
            Log::error('Error updating excursion: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'excursion_id' => $excursion->id
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Произошла ошибка при обновлении экскурсии. Пожалуйста, попробуйте позже.');
        }
    }

    public function destroy($id)
    {
        try {
            $excursion = Excursion::findOrFail($id);

            // Удаление всех фотографий экскурсии
            foreach ($excursion->photos as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
            }
            $excursion->photos()->delete();

            $excursion->delete();

            return redirect()->route('admin.excursions.index')
                ->with('success', 'Экскурсия успешно удалена');
        } catch (\Exception $e) {
            Log::error('Error deleting excursion: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'excursion_id' => $id
            ]);
            return redirect()->back()
                ->with('error', 'Произошла ошибка при удалении экскурсии. Пожалуйста, попробуйте позже.');
        }
    }

    public function book(Request $request, Excursion $excursion)
    {
        try {
            $validated = $request->validate([
                'group_type' => 'required|in:a,b,c',
                'number_of_people' => 'required|integer|min:1|max:10',
                'excursion_date' => [
                    'required',
                    'date',
                    'after_or_equal:' . $excursion->start_date->format('Y-m-d'),
                    'before_or_equal:' . $excursion->end_date->format('Y-m-d')
                ]
            ]);

            // Проверяем доступность мест на конкретную дату
            $availableSeats = $excursion->availableSeats($validated['group_type'], $validated['excursion_date']);
            if ($availableSeats < $validated['number_of_people']) {
                return back()->with('error', 'К сожалению, в выбранной группе недостаточно свободных мест на указанную дату.');
            }

            // Расчет скидки
            $discount = 0;
            $discountAmount = 0;
            $finalPrice = $excursion->price * $validated['number_of_people'];

            if ($validated['number_of_people'] >= 5) {
                $discount = 10;
                $discountAmount = $finalPrice * $discount / 100;
                $finalPrice = $finalPrice - $discountAmount;
            }

            // Создаем бронирование
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'excursion_id' => $excursion->id,
                'group_type' => $validated['group_type'],
                'number_of_people' => (int)$validated['number_of_people'],
                'booking_date' => Carbon::parse($validated['excursion_date'])->format('Y-m-d'),
                'status' => 'pending',
                'discount' => $discount,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
            ]);

            // Если скидка применена, добавляем флаг для показа попапа
            if ($discount > 0) {
                return back()->with([
                    'success' => 'Экскурсия успешно забронирована. Статус бронирования можно отследить в личном кабинете.',
                    'show_discount' => true,
                    'original_price' => $excursion->price * $validated['number_of_people'],
                    'discount_amount' => $discountAmount,
                    'final_price' => $finalPrice
                ]);
            }

            return back()->with('success', 'Экскурсия успешно забронирована. Статус бронирования можно отследить в личном кабинете.');
        } catch (\Exception $e) {
            \Log::error('Ошибка при создании бронирования:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Произошла ошибка при бронировании: ' . $e->getMessage());
        }
    }

    public function adminPanel(Request $request)
    {
        try {
            // Получаем всех гидов
            $guides = Guide::all();

            // Получаем все экскурсии с фильтрацией (как в index)
            $query = Excursion::query()->with(['guide']);

            if ($request->has('search') && !empty($request->search)) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            if ($request->has('min_price') && is_numeric($request->min_price)) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->has('max_price') && is_numeric($request->max_price)) {
                $query->where('price', '<=', $request->max_price);
            }
            if ($request->has('location') && !empty($request->location)) {
                $query->where('location', $request->location);
            }
            if ($request->has('sort')) {
                $sortOrder = $request->sort === 'asc' ? 'asc' : 'desc';
                $query->orderBy('price', $sortOrder);
            } else {
                $query->latest();
            }

            $excursions = $query->get();

            // Получаем отзывы для модерации с проверкой связей
            $reviews = Review::with(['user', 'excursion'])
                ->whereHas('user')
                ->whereHas('excursion')
                ->latest()
                ->get();

            // Получаем все бронирования с проверкой связей
            $bookings = Booking::with(['user', 'excursion'])
                ->whereHas('user')
                ->whereHas('excursion')
                ->latest('booking_date')
                ->get();

            // Получаем выбранную экскурсию для редактирования
            $excursion = null;
            if ($request->has('excursion_id')) {
                $excursion = Excursion::find($request->excursion_id);
            }

            // Получаем выбранного гида
            $guide = null;
            if ($request->has('guide_id')) {
                $guide = Guide::find($request->guide_id);
            }

            return view('admin.panel', compact('guides', 'excursions', 'excursion', 'guide', 'reviews', 'bookings'));
        } catch (\Exception $e) {
            \Log::error('Ошибка в админ панели:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Произошла ошибка при загрузке админ панели. Пожалуйста, попробуйте позже.');
        }
    }

    public function storeRating(Request $request, Excursion $excursion)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $review = $excursion->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->review,
            'is_approved' => true // Автоматически одобряем отзывы с рейтингом
        ]);

        return response()->json([
            'message' => 'Отзыв успешно добавлен',
            'average_rating' => $excursion->average_rating
        ]);
    }
}