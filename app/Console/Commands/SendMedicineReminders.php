<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Telegram\Bot\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendMedicineReminders extends Command
{
    protected $signature = 'reminders:send';

    protected $description = 'Отправляет напоминания о приёме лекарств';

    protected $telegram;

    public function __construct()
    {
        parent::__construct();
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function handle()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $currentMinute = $now->format('H:i'); // Только часы и минуты

        Log::info('Checking reminders', [
            'time' => $now->toDateTimeString(),
            'today' => $today,
            'current_minute' => $currentMinute
        ]);

        $schedules = Schedule::where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            })
            ->with(['userMedicine.user', 'userMedicine.medicine', 'userMedicine.customMedicine'])
            ->get();

        Log::info('Found schedules', ['count' => $schedules->count()]);

        $sentCount = 0;

        foreach ($schedules as $schedule) {
            // Получаем время из расписания (только часы:минуты)
            $scheduleTime = substr($schedule->time, 0, 5); // Берем только HH:MM

            Log::info('Checking schedule', [
                'schedule_id' => $schedule->id,
                'schedule_time' => $scheduleTime,
                'current_minute' => $currentMinute,
                'user_medicine_id' => $schedule->user_medicine_id
            ]);

            // Проверяем время (сравниваем только часы и минуты)
            if ($scheduleTime !== $currentMinute) {
                Log::info('Schedule time mismatch', [
                    'schedule_time' => $scheduleTime,
                    'current_minute' => $currentMinute
                ]);
                continue;
            }

            // Проверяем дни недели (если указаны)
            if ($schedule->days) {
                $days = is_array($schedule->days) ? $schedule->days : json_decode($schedule->days, true);
                $currentDay = $now->dayOfWeekIso; // 1=Пн ... 7=Вс
                Log::info('Checking days', [
                    'days' => $days,
                    'current_day' => $currentDay
                ]);
                if (!in_array($currentDay, $days)) {
                    Log::info('Day mismatch', [
                        'current_day' => $currentDay,
                        'allowed_days' => $days
                    ]);
                    continue;
                }
            }

            // Проверяем frequency
            Log::info('Checking frequency', ['frequency' => $schedule->frequency]);
            if (!in_array($schedule->frequency, ['daily', 'ежедневно'])) {
                Log::info('Frequency not supported', ['frequency' => $schedule->frequency]);
                continue;
            }

            // Проверяем, не отправляли ли уже сегодня
            if ($schedule->last_notified && $schedule->last_notified->isToday()) {
                Log::info('Already notified today', [
                    'last_notified' => $schedule->last_notified->toDateTimeString()
                ]);
                continue;
            }

            Log::info('Sending reminder', ['schedule_id' => $schedule->id]);

            // Отправляем напоминание
            $this->sendReminder($schedule);

            // Обновляем last_notified
            $schedule->update(['last_notified' => $now]);

            $sentCount++;
            Log::info('Reminder sent and updated', ['schedule_id' => $schedule->id]);
        }

        $this->info("Напоминания проверены. Отправлено: {$sentCount}");
        Log::info('Reminder check completed', ['sent_count' => $sentCount]);
    }

    protected function sendReminder(Schedule $schedule)
    {
        Log::info('Sending reminder for schedule', ['schedule_id' => $schedule->id]);

        $userMedicine = $schedule->userMedicine;
        if (!$userMedicine) {
            Log::error('UserMedicine not found', ['schedule_id' => $schedule->id]);
            return;
        }

        $user = $userMedicine->user;
        if (!$user) {
            Log::error('User not found', ['user_medicine_id' => $userMedicine->id]);
            return;
        }

        Log::info('User found', [
            'user_id' => $user->id,
            'telegram_id' => $user->telegram_id
        ]);

        // Получаем данные лекарства
        $medicine = $userMedicine->medicine;
        $customMedicine = $userMedicine->customMedicine;
        $details = $medicine ?? $customMedicine;

        if (!$details) {
            Log::error('Medicine details not found', ['user_medicine_id' => $userMedicine->id]);
            return;
        }

        $name = $details->trade_name ?? 'Лекарство';

        // Отображаем оригинальное время, если есть
        $displayTime = $schedule->original_time ?? substr($schedule->time, 0, 5);

        $text = "💊 *Пора принимать лекарство!*\n\n";
        $text .= "*{$name}*\n";
        $text .= "Время: {$displayTime}\n";
        if ($userMedicine->dosage_per_intake) {
            $text .= "Доза: {$userMedicine->dosage_per_intake}\n";
        }
        if ($userMedicine->notes) {
            $text .= "Заметка: {$userMedicine->notes}\n";
        }

        $keyboard = \Telegram\Bot\Keyboard\Keyboard::make()->inline()
            ->row([
                \Telegram\Bot\Keyboard\Keyboard::inlineButton([
                    'text' => '✅ Принял',
                    'callback_data' => 'taken_' . $schedule->id
                ])
            ])
            ->row([
                \Telegram\Bot\Keyboard\Keyboard::inlineButton([
                    'text' => '⏭ Пропустить',
                    'callback_data' => 'skip_' . $schedule->id
                ])
            ]);

        try {
            Log::info('Sending Telegram message', [
                'chat_id' => $user->telegram_id,
                'schedule_id' => $schedule->id
            ]);

            $response = $this->telegram->sendMessage([
                'chat_id' => $user->telegram_id,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => $keyboard
            ]);

            Log::info('Telegram message sent successfully', [
                'message_id' => $response->messageId ?? 'unknown'
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка отправки напоминания', [
                'user_id' => $user->id,
                'telegram_id' => $user->telegram_id,
                'schedule_id' => $schedule->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
