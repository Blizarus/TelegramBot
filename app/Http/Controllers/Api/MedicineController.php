<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends ApiBaseController
{
    public function search(Request $request)
    {
        $query = trim($request->query('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $searchTerm = '%' . mb_strtolower($query) . '%';

        $medicines = Medicine::query()
            ->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(trade_name) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(inn) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(dosage_form) LIKE ?', [$searchTerm])  // ← Правильное поле!
                    ->orWhereRaw('LOWER(manufacturer) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(dosage) LIKE ?', [$searchTerm])       // ← Добавил, полезно искать по дозировке
                    ->orWhereRaw('LOWER(pack_size) LIKE ?', [$searchTerm]);   // ← По упаковке, если нужно
            })
            ->orderBy('trade_name')
            ->limit(50)
            ->get();

        return response()->json(['data' => $medicines]);
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $medicines = Medicine::orderBy('trade_name')->paginate($perPage);
        return response()->json($medicines);
    }

    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        return response()->json($medicine);
    }
}
