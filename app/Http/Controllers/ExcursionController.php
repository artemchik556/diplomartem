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
        $query = Excursion::query()->with('guide');

        // Применяем фильтры
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%'.$request->search.'%');
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

        // Фильтрация по сезону
        if ($request->has('season') && !empty($request->season)) {
            $query->where(function($q) use ($request) {
                switch ($request->season) {
                    case 'winter':
                        // Зима: декабрь, январь, февраль
                        $q->whereMonth('start_date', '>=', 12)
                        ->orWhereMonth('start_date', '<=', 2);
                        break;
                    case 'spring':
                        // Весна: март, апрель, май
                        $q->whereBetween(\DB::raw('MONTH(start_date)'), [3, 5]);
                        break;
                    case 'summer':
                        // Лето: июнь, июль, август
                        $q->whereBetween(\DB::raw('MONTH(start_date)'), [6, 8]);
                        break;
                    case 'autumn':
                        // Осень: сентябрь, октябрь, ноябрь
                        $q->whereBetween(\DB::raw('MONTH(start_date)'), [9, 11]);
                        break;
                }
            });
        }

        // Сортировка
        if ($request->has('sort')) {
            $sortOrder = $request->sort === 'asc' ? 'asc' : 'desc';
            $query->orderBy('price', $sortOrder);
        } else {
            $query->latest();
        }

        // Остальной код остается без изменений
        if ($request->ajax()) {
            $excursions = $query->paginate(9);
            
            $html = '';
            foreach ($excursions as $excursion) {
                $html .= view('partials.excursion_item', ['excursion' => $excursion])->render();
            }
            
            return response()->json([
                'html' => $html,
                'hasMore' => $excursions->hasMorePages()
            ]);
        }

        $excursions = $query->paginate(9);
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
                'preparation_level' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'detail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'group_a_seats' => 'required|integer|min:1',
                'group_b_seats' => 'required|integer|min:1',
                'group_c_seats' => 'required|integer|min:1',
                'guide_id' => 'required|exists:guides,id',
                'location' => 'required|string|max:191',
                'transport_car' => 'nullable|string',
                'transport_bus' => 'nullable|string',
                'transport_train' => 'nullable|string',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('excursions', 'public');
                $validated['image'] = $imagePath;
            }

            if ($request->hasFile('detail_image')) {
                $detailImagePath = $request->file('detail_image')->store('excursions', 'public');
                $validated['detail_image'] = $detailImagePath;
            }

            $validated['start_date'] = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $validated['end_date'] = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

            Excursion::create($validated);

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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'detail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'transport_car' => 'nullable|string',
                'transport_bus' => 'nullable|string',
                'transport_train' => 'nullable|string',
                'preparation_level' => 'required|string',
                'guide_id' => 'required|exists:guides,id',
                'group_a_seats' => 'required|integer|min:1',
                'group_b_seats' => 'required|integer|min:1',
                'group_c_seats' => 'required|integer|min:1',
            ]);

            if ($request->hasFile('image')) {
                if ($excursion->image) {
                    Storage::disk('public')->delete($excursion->image);
                }
                $validated['image'] = $request->file('image')->store('excursions', 'public');
            }

            if ($request->hasFile('detail_image')) {
                if ($excursion->detail_image) {
                    Storage::disk('public')->delete($excursion->detail_image);
                }
                $validated['detail_image'] = $request->file('detail_image')->store('excursions', 'public');
            }

            $validated['start_date'] = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $validated['end_date'] = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

            $excursion->update($validated);

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
            
            // Удаляем изображения
            if ($excursion->image) {
                Storage::disk('public')->delete($excursion->image);
            }
            if ($excursion->detail_image) {
                Storage::disk('public')->delete($excursion->detail_image);
            }
            
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

                // Проверяем доступность мест
                $availableSeats = $excursion->availableSeats($validated['group_type']);
                if ($availableSeats < $validated['number_of_people']) {
                    return back()->with('error', 'К сожалению, в выбранной группе недостаточно свободных мест.');
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
                    'name' => Auth::user()->name,
                ]);

                // Обновляем количество доступных мест
                $seatsField = "group_{$validated['group_type']}_seats";
                $excursion->$seatsField = $availableSeats - $validated['number_of_people'];
                $excursion->save();

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
}
