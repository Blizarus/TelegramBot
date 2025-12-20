<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    protected function authUser(Request $request)
    {
        $telegramId = $request->input('telegram_id') ?? $request->header('X-Telegram-Id');

        if (!$telegramId) {
            abort(401, 'Telegram ID required');
        }

        return User::where('telegram_id', $telegramId)->firstOrFail();
    }
}
