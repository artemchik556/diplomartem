<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $guides = Guide::paginate(8); // вместо Guide::all()
        return view('about', compact('guides'));
    }
}
