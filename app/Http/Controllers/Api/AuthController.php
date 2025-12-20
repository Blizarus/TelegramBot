<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends ApiBaseController
{
    public function registerOrLogin(Request $request)
    {
        $request->validate([
            'telegram_id' => 'required|integer',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
        ]);

        $user = User::updateOrCreate(
            ['telegram_id' => $request->telegram_id],
            [
                'name' => $request->name ?? 'Пользователь Telegram',
                'phone' => $request->phone,
            ]
        );

        return response()->json([
            'success' => true,
            'user' => $user->only(['id', 'telegram_id', 'name', 'phone'])
        ]);
    }
}
