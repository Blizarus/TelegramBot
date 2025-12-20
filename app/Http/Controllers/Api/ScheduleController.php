<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends ApiBaseController
{
    public function index(Request $request)
    {
        $user = $this->authUser($request);

        $schedules = Schedule::whereHas('userMedicine', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['userMedicine.medicine', 'userMedicine.customMedicine'])
            ->orderBy('time')
            ->get()
            ->map(function($schedule) {
                return $this->formatSchedule($schedule);
            });

        return response()->json(['data' => $schedules]);
    }

    public function store(Request $request)
    {
        $user = $this->authUser($request);

        $validated = $request->validate([
            'user_medicine_id' => 'required|exists:user_medicines,id',
            'time' => 'required',
            'frequency' => 'required|string|max:50',
            'days' => 'nullable|json',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // Проверка принадлежности user_medicine
        $user->medicines()->findOrFail($validated['user_medicine_id']);

        $schedule = Schedule::create($validated);

        // Загружаем отношения
        $schedule->load(['userMedicine.medicine', 'userMedicine.customMedicine']);

        return response()->json($this->formatSchedule($schedule), 201);
    }

    public function show(Request $request, $id)
    {
        $user = $this->authUser($request);

        $schedule = Schedule::whereHas('userMedicine', fn($q) => $q->where('user_id', $user->id))
            ->with(['userMedicine.medicine', 'userMedicine.customMedicine'])
            ->findOrFail($id);

        return response()->json($this->formatSchedule($schedule));
    }

    public function update(Request $request, $id)
    {
        $user = $this->authUser($request);

        $schedule = Schedule::whereHas('userMedicine', fn($q) => $q->where('user_id', $user->id))
            ->findOrFail($id);

        $validated = $request->validate([
            'time' => 'required',
            'frequency' => 'required|string|max:50',
            'days' => 'nullable|json',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $schedule->update($validated);

        // Загружаем обновленные отношения
        $schedule->load(['userMedicine.medicine', 'userMedicine.customMedicine']);

        return response()->json($this->formatSchedule($schedule));
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->authUser($request);

        $schedule = Schedule::whereHas('userMedicine', fn($q) => $q->where('user_id', $user->id))
            ->findOrFail($id);

        $schedule->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Форматирует объект Schedule для ответа API
     */
    private function formatSchedule(Schedule $schedule): array
    {
        $data = $schedule->toArray();

        // Добавляем информацию о лекарстве
        if ($schedule->userMedicine) {
            $data['user_medicine'] = $schedule->userMedicine->toArray();

            // Добавляем details из связанных моделей
            if ($schedule->userMedicine->medicine) {
                $data['user_medicine']['details'] = $schedule->userMedicine->medicine->toArray();
            } elseif ($schedule->userMedicine->customMedicine) {
                $data['user_medicine']['details'] = $schedule->userMedicine->customMedicine->toArray();
            } else {
                $data['user_medicine']['details'] = null;
            }
        } else {
            $data['user_medicine'] = null;
        }

        return $data;
    }
}
