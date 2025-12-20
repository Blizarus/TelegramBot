<?php

use App\Http\Controllers\Telegram\TelegramBotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\CustomMedicineController;
use App\Http\Controllers\Api\UserMedicineController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\FamilyController;

Route::post('/register', [AuthController::class, 'registerOrLogin']);

Route::prefix('medicines')->group(function () {
    Route::get('/search', [MedicineController::class, 'search']);
    Route::get('/{id}', [MedicineController::class, 'show']);
    Route::get('/', [MedicineController::class, 'index']);
});

Route::middleware([])->group(function () {
    // Все роуты ниже требуют telegram_id в запросе
    Route::post('/custom-medicines', [CustomMedicineController::class, 'store']);
    Route::get('/custom-medicines', [CustomMedicineController::class, 'index']);

    Route::apiResource('user-medicines', UserMedicineController::class);
    Route::apiResource('schedules', ScheduleController::class);

    Route::post('/family/create', [FamilyController::class, 'create']);
});

Route::post('/bot/webhook', [TelegramBotController::class, 'webhook']);
Route::get('/bot/set-webhook', [TelegramBotController::class, 'setWebhook']); // вызови один раз
