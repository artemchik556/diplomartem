<?php

namespace App\Http\Controllers;

use App\Models\Excursion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularExcursions = Excursion::with(['reviews' => function($q) {
            $q->where('is_approved', true)
              ->whereNotNull('rating')
              ->where('rating', '>', 0);
        }])
        ->withAvg(['reviews' => function($q) {
            $q->where('is_approved', true)
              ->whereNotNull('rating')
              ->where('rating', '>', 0);
        }], 'rating')
        ->orderBy('reviews_avg_rating', 'desc')
        ->take(4)
        ->get();

        return view('index', compact('popularExcursions'));
    }
} 