<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::all();
        return view('admin.guides.index', compact('guides'));
    }

    public function create()
    {
        return view('admin.guides.create');
    }

    public function store(Request $request)
    {
        try {
            // Логируем входные данные
            Log::info('Store request data: ' . json_encode($request->all()));

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'description' => 'nullable|string'
            ]);

            Log::info('Validated data before image handling: ' . json_encode($validated));

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('guides', 'public');
                Log::info('Stored image path: ' . $path);
                $validated['image'] = $path;
            } else {
                Log::info('No image uploaded');
                $validated['image'] = null;
            }

            Log::info('Validated data after image handling: ' . json_encode($validated));

            // Ручное создание записи
            $guide = new Guide();
            $guide->name = $validated['name'];
            $guide->position = $validated['position'];
            $guide->description = $validated['description'] ?? null;
            $guide->image = $validated['image'];
            $guide->save();

            Log::info('Guide saved manually: ' . json_encode($guide->toArray()));

            return redirect()->route('admin.guides.index')->with('success', 'Гид успешно добавлен');
        } catch (\Exception $e) {
            Log::error('Error saving guide: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Ошибка при сохранении гида: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Guide $guide)
    {
        return view('admin.guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide)
    {
        try {
            // Логируем входные данные
            Log::info('Update request data: ' . json_encode($request->all()));

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'description' => 'nullable|string',
                'remove_image' => 'nullable|in:1,0'
            ]);

            Log::info('Validated data before image handling: ' . json_encode($validated));

            $this->handleGuideImage($request, $guide, $validated);

            Log::info('Validated data after image handling: ' . json_encode($validated));

            // Ручное обновление записи
            $guide->name = $validated['name'];
            $guide->position = $validated['position'];
            $guide->description = $validated['description'] ?? null;
            $guide->image = $validated['image'];
            $guide->save();

            Log::info('Guide updated manually: ' . json_encode($guide->fresh()->toArray()));

            return redirect()->route('admin.guides.index')
                ->with('success', 'Данные гида успешно обновлены');
        } catch (\Exception $e) {
            Log::error('Error updating guide: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Ошибка при обновлении гида: ' . $e->getMessage())->withInput();
        }
    }

    protected function handleGuideImage(Request $request, Guide $guide, array &$validated)
    {
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($guide->image) {
                Storage::disk('public')->delete($guide->image);
                Log::info('Deleted old image: ' . $guide->image);
            }
            $validated['image'] = null;
            return;
        }

        if ($request->hasFile('image')) {
            if ($guide->image) {
                Storage::disk('public')->delete($guide->image);
                Log::info('Deleted old image: ' . $guide->image);
            }
            $path = $request->file('image')->store('guides', 'public');
            Log::info('Stored new image path: ' . $path);
            $validated['image'] = $path;
            return;
        }

        Log::info('No new image uploaded, keeping old image: ' . $guide->image);
        $validated['image'] = $guide->image;
    }

    public function destroy(Guide $guide)
    {
        try {
            if ($guide->image) {
                Storage::disk('public')->delete($guide->image);
                Log::info('Deleted image on destroy: ' . $guide->image);
            }
            $guide->delete();
            Log::info('Guide deleted: ' . $guide->id);

            return redirect()->route('admin.guides.index')
                ->with('success', 'Гид успешно удален');
        } catch (\Exception $e) {
            Log::error('Error deleting guide: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ошибка при удалении гида: ' . $e->getMessage());
        }
    }
}