<?php

namespace App\Http\Controllers\Telegram\Commands;

use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SetReminderCommand extends Command
{
    protected $steps = [
        'waiting_time',
        'waiting_frequency',
        'waiting_days',
        'waiting_start_date',
        'waiting_end_date',
        'confirm'
    ];

    public function handle($update)
    {
        // Эта команда вызывается только через callback
    }

    public function start($chatId, $userTelegramId, $userMedicineId)
    {
        Cache::put("reminder_medicine_{$userTelegramId}", $userMedicineId, 1800);
        Cache::put("reminder_step_{$userTelegramId}", 'waiting_time', 1800);
        Cache::forget("reminder_data_{$userTelegramId}");

        $this->askTime($chatId);
    }

    public function processStep($chatId, $userTelegramId, $text)
    {
        $step = Cache::get("reminder_step_{$userTelegramId}");

        if ($step === 'waiting_time') {
            if (!preg_match('/^\d{1,2}:\d{2}$/', $text) || !strtotime("2025-01-01 $text")) {
                $this->sendMessage($chatId, "Неверный формат времени. Введите в формате ЧЧ:ММ (например, 08:30):");
                return;
            }
            $this->saveData('time', $text, $userTelegramId);
            $this->nextStep('waiting_frequency', $userTelegramId);
            $this->askFrequency($chatId);
        }

        elseif ($step === 'waiting_frequency') {
            $allowed = ['ежедневно', 'каждые 12 часов', 'каждые 8 часов', '2 раза в день', '1 раз в день'];
            if (!in_array(mb_strtolower($text), array_map('mb_strtolower', $allowed))) {
                $this->sendMessage($chatId, "Выберите один из вариантов:\nежедневно\nкаждые 12 часов\nкаждые 8 часов\n2 раза в день\n1 раз в день");
                return;
            }
            $frequency = mb_strtolower($text) === '1 раз в день' ? 'daily' : mb_strtolower($text);
            $this->saveData('frequency', $frequency, $userTelegramId);

            if (in_array($frequency, ['ежедневно', 'daily'])) {
                $this->nextStep('waiting_start_date', $userTelegramId);
                $this->askStartDate($chatId);
            } else {
                $this->nextStep('waiting_days', $userTelegramId);
                $this->askDays($chatId);
            }
        }

        elseif ($step === 'waiting_days') {
            $this->saveData('days', $text, $userTelegramId);
            $this->nextStep('waiting_start_date', $userTelegramId);
            $this->askStartDate($chatId);
        }

        elseif ($step === 'waiting_start_date') {
            $startDate = $this->parseDate($text);
            if (!$startDate) {
                $this->sendMessage($chatId, "Неверный формат даты. Введите ГГГГ-ММ-ДД или 'сегодня' / 'завтра':");
                return;
            }
            $this->saveData('start_date', $startDate, $userTelegramId);
            $this->nextStep('waiting_end_date', $userTelegramId);
            $this->askEndDate($chatId);
        }

        elseif ($step === 'waiting_end_date') {
            $endDate = null;
            if (mb_strtolower(trim($text)) !== 'без конца' && !empty($text)) {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $text)) {
                    $this->sendMessage($chatId, "Неверный формат. Введите ГГГГ-ММ-ДД или 'без конца':");
                    return;
                }
                $endDate = $text;
            }
            $this->saveData('end_date', $endDate, $userTelegramId);
            $this->nextStep('confirm', $userTelegramId);
            $this->showConfirmation($chatId, $userTelegramId);
        }
    }
    public function confirm($chatId, $userTelegramId)
    {
        $data = Cache::get("reminder_data_{$userTelegramId}", []);
        $userMedicineId = Cache::get("reminder_medicine_{$userTelegramId}");

        // Конвертируем московское время в UTC
        $moscowTime = $data['time'];
        $utcTime = $this->convertMoscowToUtc($moscowTime);

        $payload = [
            'telegram_id' => $userTelegramId,
            'user_medicine_id' => $userMedicineId,
            'time' => $utcTime, // Сохраняем в UTC
            'original_time' => $moscowTime, // Сохраняем оригинальное время для отображения
            'frequency' => $data['frequency'],
            'days' => $data['days'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'is_active' => true
        ];

        $response = Http::post("{$this->apiUrl}/schedules", $payload);

        if ($response->successful()) {
            $this->sendMessage($chatId, "Напоминание успешно установлено! ⏰\nВы будете получать уведомления о приёме лекарства.");
        } else {
            $error = $response->json('error') ?? 'Неизвестная ошибка';
            $this->sendMessage($chatId, "Не удалось установить напоминание: {$error}");
        }

        $this->cleanup($userTelegramId);
    }

// Добавьте этот метод для конвертации
    protected function convertMoscowToUtc($moscowTime)
    {
        try {
            // Создаем объект DateTime с московским временем
            $dateTime = \Carbon\Carbon::createFromFormat('H:i', $moscowTime, 'Europe/Moscow');
            // Конвертируем в UTC
            $dateTime->setTimezone('UTC');
            return $dateTime->format('H:i');
        } catch (\Exception $e) {
            // Если ошибка, возвращаем оригинальное время
            \Log::error('Error converting Moscow time to UTC', [
                'moscow_time' => $moscowTime,
                'error' => $e->getMessage()
            ]);
            return $moscowTime;
        }
    }

    public function cancel($chatId, $userTelegramId)
    {
        $this->sendMessage($chatId, "Установка напоминания отменена.");
        $this->cleanup($userTelegramId);
    }

    // Вспомогательные методы
    protected function askTime($chatId)
    {
        $this->sendMessage($chatId, "В какое время принимать лекарство?\nВведите в формате ЧЧ:ММ (например, 09:00 или 20:30):");
    }

    protected function askFrequency($chatId)
    {
        $keyboard = Keyboard::make()->row([
            Keyboard::button('ежедневно'),
            Keyboard::button('каждые 12 часов')
        ])->row([
            Keyboard::button('каждые 8 часов'),
            Keyboard::button('2 раза в день')
        ]);

        $this->sendMessage($chatId, "Как часто принимать?", $keyboard);
    }

    protected function askDays($chatId)
    {
        $this->sendMessage($chatId, "В какие дни недели? (перечислите через запятую, например: пн, ср, пт)");
    }

    protected function askStartDate($chatId)
    {
        $this->sendMessage($chatId, "С какой даты начинать напоминания?\nВведите дату (ГГГГ-ММ-ДД) или 'сегодня' / 'завтра':");
    }

    protected function askEndDate($chatId)
    {
        $this->sendMessage($chatId, "До какой даты напоминать? (или напишите 'без конца')");
    }

    protected function showConfirmation($chatId, $userTelegramId)
    {
        $data = Cache::get("reminder_data_{$userTelegramId}", []);
        $medicineId = Cache::get("reminder_medicine_{$userTelegramId}");

        if (empty($data) || !$medicineId) {
            $this->sendMessage($chatId, "Ошибка: данные напоминания потеряны. Начните заново.");
            $this->cleanup($userTelegramId);
            return;
        }

        $response = Http::get("{$this->apiUrl}/user-medicines/{$medicineId}", ['telegram_id' => $userTelegramId]);
        if ($response->failed()) {
            $this->sendMessage($chatId, "Не удалось получить информацию о лекарстве.");
            $this->cleanup($userTelegramId);
            return;
        }

        $medicine = $response->json();
        // Теперь details - это массив, а не объект
        $name = $medicine['details']['trade_name'] ?? 'Лекарство';

        $text = "Подтвердите установку напоминания:\n\n";
        $text .= "*Лекарство:* {$name}\n";
        $text .= "*Время:* " . ($data['time'] ?? 'не указано') . "\n";
        $text .= "*Частота:* " . ($data['frequency'] ?? 'не указана') . "\n";
        if (!empty($data['days'])) {
            $text .= "*Дни:* {$data['days']}\n";
        }
        $text .= "*Начало:* " . ($data['start_date'] ?? 'не указано') . "\n";
        $text .= "*Окончание:* " . ($data['end_date'] ?? 'без конца') . "\n";

        $keyboard = Keyboard::make()->inline()
            ->row([
                Keyboard::inlineButton(['text' => '✅ Подтвердить', 'callback_data' => 'confirm_reminder']),
                Keyboard::inlineButton(['text' => '❌ Отмена', 'callback_data' => 'cancel_reminder'])
            ]);

        $this->sendMessage($chatId, $text, $keyboard);
    }

    protected function saveData($key, $value, $userTelegramId)
    {
        $data = Cache::get("reminder_data_{$userTelegramId}", []);
        $data[$key] = $value;
        Cache::put("reminder_data_{$userTelegramId}", $data, 1800);
    }

    protected function nextStep($next, $userTelegramId)
    {
        Cache::put("reminder_step_{$userTelegramId}", $next, 1800);
    }

    protected function cleanup($userTelegramId)
    {
        Cache::forget("reminder_medicine_{$userTelegramId}");
        Cache::forget("reminder_step_{$userTelegramId}");
        Cache::forget("reminder_data_{$userTelegramId}");
    }

    protected function parseDate($text)
    {
        $text = mb_strtolower(trim($text));
        if ($text === 'сегодня') {
            return now()->format('Y-m-d');
        }
        if ($text === 'завтра') {
            return now()->addDay()->format('Y-m-d');
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $text)) {
            return $text;
        }
        return false;
    }

    protected function sendMessage($chatId, $text, $replyMarkup = null)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => $replyMarkup ?? Keyboard::remove()
        ]);
    }
}
