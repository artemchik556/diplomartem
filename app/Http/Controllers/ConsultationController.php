<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $consultation = Consultation::create(array_merge($validated, [
            'status' => 'new',
            'admin_notes' => null
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Заявка успешно отправлена!',
            'data' => $consultation
        ], 201);
    }

    public function index(Request $request)
    {
        $consultations = Consultation::when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.consultations.index', compact('consultations'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
            // Добавьте другие поля если нужно
        ]);
    
        try {
            $consultation->update($validated);
            
            return redirect()->back()
                ->with('success', 'Заметки успешно обновлены!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ошибка при сохранении: ' . $e->getMessage());
        }
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return request()->wantsJson()
            ? response()->json(['success' => true])
            : back()->with('success', 'Заявка удалена!');
    }
}