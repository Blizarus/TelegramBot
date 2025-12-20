<?php

namespace App\Http\Controllers\Telegram\Commands;


use App\Models\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Keyboard\Keyboard;

class CallbackCommand extends Command
{
    public function handle($update)
    {
        $callbackQuery = $update->callback_query;
        $chatId = $callbackQuery->message->chat->id;
        $userTelegramId = $callbackQuery->from->id;
        $data = $callbackQuery->data;

        $this->telegram->answerCallbackQuery(['callback_query_id' => $callbackQuery->id]);

        if (str_starts_with($data, 'add_med_')) {
            $medicineId = str_replace('add_med_', '', $data);
            $this->addMedicine($chatId, $userTelegramId, $medicineId);
        } elseif ($data === 'cancel_search') {
            $this->sendMessage($chatId, "Поиск отменён.");
            Cache::forget("state_{$userTelegramId}");
            Cache::forget("search_results_{$userTelegramId}");
        } elseif (str_starts_with($data, 'list_prev_') || str_starts_with($data, 'list_next_')) {
            $page = str_replace(['list_prev_', 'list_next_'], '', $data);
            $messageId = $callbackQuery->message->message_id;
            (new ListCommand())->showPage($chatId, $userTelegramId, $page, $messageId);
        } elseif ($data === 'list_close') {
            $this->telegram->deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $callbackQuery->message->message_id
            ]);
            Cache::forget("list_message_{$userTelegramId}");
        } elseif (str_starts_with($data, 'delete_med_')) {
            $itemId = str_replace('delete_med_', '', $data);
            $messageId = $callbackQuery->message->message_id;
            $this->deleteMedicine($chatId, $userTelegramId, $itemId, $messageId);
        }
        elseif ($data === 'refresh_apteka') {
            $messageId = $callbackQuery->message->message_id;
            (new MyAptekaCommand())->showApteka($chatId, $userTelegramId, $messageId);
        } elseif (str_starts_with($data, 'taken_')) {
            $scheduleId = str_replace('taken_', '', $data);
            $this->markAsTaken($chatId, $userTelegramId, $scheduleId);
        }
        elseif (str_starts_with($data, 'skip_')) {
            $scheduleId = str_replace('skip_', '', $data);
            $this->markAsSkipped($chatId, $userTelegramId, $scheduleId);
        } elseif (str_starts_with($data, 'set_reminder_')) {
            $userMedicineId = str_replace('set_reminder_', '', $data);
            (new SetReminderCommand())->start($chatId, $userTelegramId, $userMedicineId);
        }
        elseif ($data === 'confirm_reminder') {
            (new SetReminderCommand())->confirm($chatId, $userTelegramId);
        }
        elseif ($data === 'cancel_reminder') {
            (new SetReminderCommand())->cancel($chatId, $userTelegramId);
        }
    }

    protected function deleteMedicine($chatId, $userTelegramId, $itemId, $messageId)
    {
        $response = Http::delete("{$this->apiUrl}/user-medicines/{$itemId}", [
            'telegram_id' => $userTelegramId
        ]);

        if ($response->successful()) {
            $this->sendMessage($chatId, "Лекарство удалено из аптечки.");
            // Обновляем список аптечки
            (new MyAptekaCommand())->showApteka($chatId, $userTelegramId, $messageId);
        } else {
            $error = $response->json('error') ?? 'Неизвестная ошибка';
            $this->sendMessage($chatId, "Не удалось удалить: {$error}");
        }
    }
    protected function addMedicine($chatId, $userTelegramId, $medicineId)
    {
        $response = Http::post("{$this->apiUrl}/user-medicines", [
            'telegram_id' => $userTelegramId,
            'medicine_id' => $medicineId,
            'quantity' => 30
        ]);

        if ($response->successful()) {
            $item = $response->json();
            // Безопасное получение названия
            $name = 'Лекарство';
            if (isset($item['details']) && is_array($item['details'])) {
                $name = $item['details']['trade_name'] ?? 'Лекарство';
            } elseif (isset($item['medicine']) && is_array($item['medicine'])) {
                $name = $item['medicine']['trade_name'] ?? 'Лекарство';
            } elseif (isset($item['custom_medicine']) && is_array($item['custom_medicine'])) {
                $name = $item['custom_medicine']['trade_name'] ?? 'Лекарство';
            }

            $this->sendMessage($chatId, "✅ «{$name}» успешно добавлено в аптечку!");
        } else {
            $error = $response->json('error') ?? $response->status();
            $this->sendMessage($chatId, "❌ Не удалось добавить: {$error}");
        }

        Cache::forget("search_results_{$userTelegramId}");
        Cache::forget("list_results_{$userTelegramId}");
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

    protected function markAsTaken($chatId, $userTelegramId, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);

        if ($schedule && $schedule->userMedicine->user->telegram_id == $userTelegramId) {
            // Можно добавить логирование приёма, но пока просто отметим
            $this->sendMessage($chatId, "Отлично! ✅ Приём отмечен.");
        }
    }

    protected function markAsSkipped($chatId, $userTelegramId, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);

        if ($schedule && $schedule->userMedicine->user->telegram_id == $userTelegramId) {
            $this->sendMessage($chatId, "Пропущено ⏭\nНе забудьте принять в следующий раз!");
            // Можно обновить last_notified, чтобы не спамить
            $schedule->update(['last_notified' => now()]);
        }
    }
}
