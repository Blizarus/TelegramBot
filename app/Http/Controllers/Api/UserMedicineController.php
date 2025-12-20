<?php

namespace App\Http\Controllers\Api;

use App\Models\UserMedicine;
use Illuminate\Http\Request;

class UserMedicineController extends ApiBaseController
{
    public function index(Request $request)
    {
        $user = $this->authUser($request);
        $items = $user->medicines()
            ->with(['medicine', 'customMedicine'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                return $this->formatItem($item);
            });

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'telegram_id' => 'required|integer',
            'medicine_id' => 'sometimes|nullable|exists:medicines,id',
            'custom_medicine_id' => 'sometimes|nullable|exists:custom_medicines,id',
            'quantity' => 'nullable|integer|min:0',
            'total_quantity' => 'nullable|integer|min:0',
            'expiration_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'dosage_per_intake' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $user = \App\Models\User::where('telegram_id', $validated['telegram_id'])
            ->firstOrFail();

        // Безопасно получаем ID
        $medicineId = $validated['medicine_id'] ?? null;
        $customMedicineId = $validated['custom_medicine_id'] ?? null;

        // Обязательно должен быть хотя бы один
        if (!$medicineId && !$customMedicineId) {
            return response()->json([
                'error' => 'medicine_id или custom_medicine_id обязателен'
            ], 422);
        }

        // И не больше одного
        if ($medicineId && $customMedicineId) {
            return response()->json([
                'error' => 'Укажите только один источник лекарства'
            ], 422);
        }

        // Проверка на дубликат
        $exists = $user->medicines()
            ->where('medicine_id', $medicineId)
            ->where('custom_medicine_id', $customMedicineId)
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => 'Это лекарство уже есть в вашей аптечке'
            ], 422);
        }

        // Создание записи
        $item = $user->medicines()->create([
            'medicine_id' => $medicineId,
            'custom_medicine_id' => $customMedicineId,
            'quantity' => $validated['quantity'] ?? 30,
            'total_quantity' => $validated['total_quantity'] ?? null,
            'expiration_date' => $validated['expiration_date'] ?? null,
            'purchase_price' => $validated['purchase_price'] ?? null,
            'dosage_per_intake' => $validated['dosage_per_intake'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $item->load(['medicine', 'customMedicine']);

        return response()->json($this->formatItem($item), 201);
    }

    public function show(Request $request, $id)
    {
        $user = $this->authUser($request);

        // Загружаем оба возможных отношения
        $item = $user->medicines()
            ->with(['medicine', 'customMedicine'])
            ->findOrFail($id);

        return response()->json($this->formatItem($item));
    }

    public function update(Request $request, $id)
    {
        $user = $this->authUser($request);
        $item = $user->medicines()->findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:0',
            'total_quantity' => 'nullable|integer|min:0',
            'expiration_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'dosage_per_intake' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $item->update($validated);

        // Загружаем обновленные отношения
        $item->load(['medicine', 'customMedicine']);

        return response()->json($this->formatItem($item));
    }

    public function destroy(Request $request, $id)
    {
        $telegramId = $request->input('telegram_id');

        if ($telegramId) {
            $user = \App\Models\User::where('telegram_id', $telegramId)->firstOrFail();
        } else {
            $user = $this->authUser($request);
        }

        $item = $user->medicines()->findOrFail($id);
        $item->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Форматирует объект UserMedicine, добавляя поле details
     * и преобразуя его в массив
     */
    private function formatItem(UserMedicine $item): array
    {
        // Используем только базовые атрибуты модели
        $data = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'medicine_id' => $item->medicine_id,
            'custom_medicine_id' => $item->custom_medicine_id,
            'quantity' => $item->quantity,
            'total_quantity' => $item->total_quantity,
            'expiration_date' => $item->expiration_date,
            'purchase_price' => $item->purchase_price,
            'dosage_per_intake' => $item->dosage_per_intake,
            'notes' => $item->notes,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];

        // Добавляем поле details из связанных моделей
        if ($item->medicine) {
            $data['details'] = $item->medicine->toArray();
        } elseif ($item->customMedicine) {
            $data['details'] = $item->customMedicine->toArray();
        } else {
            $data['details'] = null;
        }

        // Также можно добавить отдельно связанные модели, если нужно
        $data['medicine'] = $item->medicine ? $item->medicine->toArray() : null;
        $data['custom_medicine'] = $item->customMedicine ? $item->customMedicine->toArray() : null;

        return $data;
    }
}
