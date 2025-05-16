<?php

use App\Http\Controllers\ExcursionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\GuideController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoutePointController;
use App\Http\Controllers\CallRequestController;
use Illuminate\Support\Facades\Route;

// Публичные маршруты (доступны всем)
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/excursions', [ExcursionController::class, 'index'])->name('excursions'); // Список экскурсий для пользователей
Route::get('/excurcion', [ExcursionController::class, 'index'])->name('excurcion'); // Альтернативный маршрут для списка экскурсий
Route::get('/excursion/{id}', [ExcursionController::class, 'show'])->name('excursion.detail'); // Детальный просмотр экскурсии
Route::post('/excursions/{excursion}/book', [ExcursionController::class, 'book'])
    ->name('excursions.book')
    ->middleware('auth'); // Бронирование (требуется авторизация)

// Маршруты для отзывов
Route::post('/excursions/{excursion}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');

// Админские маршруты для управления отзывами
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::patch('/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::get('/pereval', [RoutePointController::class, 'index'])->name('pereval');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Консультации
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

// Профиль
Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');

// Админские маршруты (требуется авторизация)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Главная страница админ-панели
    Route::get('/', [ExcursionController::class, 'adminPanel'])->name('admin.dashboard');

    // Управление экскурсиями (требуется роль admin)
    Route::middleware('admin')->group(function () {
        Route::get('/excursions', [ExcursionController::class, 'index'])->name('admin.excursions.index');
        Route::get('/excursions/create', [ExcursionController::class, 'create'])->name('admin.excursions.create');
        Route::post('/excursions', [ExcursionController::class, 'store'])->name('admin.excursions.store');
        Route::get('/excursions/{excursion}/edit', [ExcursionController::class, 'edit'])->name('admin.excursions.edit');
        Route::put('/excursions/{excursion}', [ExcursionController::class, 'update'])->name('admin.excursions.update');
        Route::delete('/excursions/{excursion}', [ExcursionController::class, 'destroy'])->name('admin.excursions.destroy');

        // Управление консультациями
        Route::get('/consultations', [ConsultationController::class, 'index'])->name('admin.consultations.index');
        Route::put('/consultations/{consultation}', [ConsultationController::class, 'update'])->name('admin.consultations.update');
        Route::delete('/consultations/{consultation}', [ConsultationController::class, 'destroy'])->name('admin.consultations.destroy');
    });

    // Управление гидами (требуется роль admin)
    Route::middleware('admin')->group(function () {
        Route::resource('guides', GuideController::class)->names('admin.guides');
    });
});

Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/route-points', [RoutePointController::class, 'store'])->name('route-points.store');
    Route::put('/route-points/{routePoint}', [RoutePointController::class, 'update'])->name('route-points.update');
    Route::delete('/route-points/{routePoint}', [RoutePointController::class, 'destroy'])->name('route-points.destroy');
});


Route::post('/clear-discount-session', [BookingController::class, 'clearDiscountSession'])->name('clear.discount.session');


