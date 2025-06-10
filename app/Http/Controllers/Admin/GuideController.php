<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuideController extends Controller
{
    public function create()
    {
        return view('admin.guides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'position' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'experience' => 'nullable|integer|min:0', // Валидация для стажа
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('guides', 'public');
            $validated['image'] = $imagePath;
        }

        Guide::create($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'list-guides'])
            ->with('success', 'Гид успешно добавлен');
    }

    public function edit(Guide $guide)
    {
        return view('admin.guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'position' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'experience' => 'nullable|integer|min:0', // Валидация для стажа
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если есть
            if ($guide->image) {
                Storage::disk('public')->delete($guide->image);
            }
            $imagePath = $request->file('image')->store('guides', 'public');
            $validated['image'] = $imagePath;
        } elseif ($request->input('remove_image') && $guide->image) {
            // Удаляем изображение, если установлен флажок
            Storage::disk('public')->delete($guide->image);
            $validated['image'] = null;
        }

        $guide->update($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'list-guides'])
            ->with('success', 'Гид успешно обновлен');
    }

    public function destroy(Guide $guide)
    {
        if ($guide->image) {
            Storage::disk('public')->delete($guide->image);
        }

        $guide->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'list-guides'])
            ->with('success', 'Гид успешно удален');
    }
}