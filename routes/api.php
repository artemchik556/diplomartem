<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExcursionController;
use App\Models\Excursion;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::get('/excursion/{excursion}/available-seats', function (Excursion $excursion, Request $request) {
        try {
            $date = $request->query('date');
            if (!$date) {
                return response()->json(['error' => 'Date is required'], 400);
            }

            \Log::info('Checking available seats', [
                'excursion_id' => $excursion->id,
                'date' => $date
            ]);

            $seats = [
                'a' => $excursion->availableSeats('a', $date),
                'b' => $excursion->availableSeats('b', $date),
                'c' => $excursion->availableSeats('c', $date)
            ];

            \Log::info('Available seats', $seats);

            return response()->json($seats);
        } catch (\Exception $e) {
            \Log::error('Error in available seats API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->name('api.excursion.available-seats');
}); 