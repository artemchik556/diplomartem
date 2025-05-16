<?php

namespace App\Http\Controllers;

use App\Models\RoutePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoutePointController extends Controller
{
    public function index()
    {
        $routePoints = RoutePoint::orderBy('date')->get();
        return view('pereval', compact('routePoints'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'date' => 'required|date',
            'order' => 'required|integer'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('route-points', 'public');
            $validated['image'] = $path;
        }

        RoutePoint::create($validated);

        return redirect()->back()->with('success', 'Точка маршрута успешно добавлена');
    }

    public function update(Request $request, RoutePoint $routePoint)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'date' => 'required|date',
            'order' => 'required|integer'
        ]);

        if ($request->hasFile('image')) {
            if ($routePoint->image) {
                Storage::disk('public')->delete($routePoint->image);
            }
            $path = $request->file('image')->store('route-points', 'public');
            $validated['image'] = $path;
        }

        $routePoint->update($validated);

        return redirect()->back()->with('success', 'Точка маршрута успешно обновлена');
    }

    public function destroy(RoutePoint $routePoint)
    {
        if ($routePoint->image) {
            Storage::disk('public')->delete($routePoint->image);
        }
        
        $routePoint->delete();

        return redirect()->back()->with('success', 'Точка маршрута успешно удалена');
    }
}
