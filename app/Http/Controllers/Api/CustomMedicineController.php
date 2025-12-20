<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomMedicine;
use Illuminate\Http\Request;

class CustomMedicineController extends ApiBaseController
{
    // Список кастомных лекарств пользователя
    public function index(Request $request)
    {
        $user = $this->authUser($request);
        $customs = $user->customMedicines()->orderBy('trade_name')->get();

        return response()->json(['data' => $customs]);
    }

    // Создать новое кастомное лекарство
    public function store(Request $request)
    {
        $user = $this->authUser($request);

        $validated = $request->validate([
            'trade_name' => 'required|string|max:255',
            'inn' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:150',
            'dosage' => 'nullable|string|max:100',
            'pack_size' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $custom = $user->customMedicines()->create($validated);

        return response()->json($custom, 201);
    }

    // Показать одно
    public function show(Request $request, $id)
    {
        $user = $this->authUser($request);
        $custom = $user->customMedicines()->findOrFail($id);

        return response()->json($custom);
    }

    // Обновить
    public function update(Request $request, $id)
    {
        $user = $this->authUser($request);
        $custom = $user->customMedicines()->findOrFail($id);

        $validated = $request->validate([
            'trade_name' => 'required|string|max:255',
            'inn' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:150',
            'dosage' => 'nullable|string|max:100',
            'pack_size' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $custom->update($validated);

        return response()->json($custom);
    }

    // Удалить
    public function destroy(Request $request, $id)
    {
        $user = $this->authUser($request);
        $custom = $user->customMedicines()->findOrFail($id);

        $custom->delete();

        return response()->json(['success' => true]);
    }
}
