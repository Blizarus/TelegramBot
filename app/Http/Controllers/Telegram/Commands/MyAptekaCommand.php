<?php

namespace App\Http\Controllers\Telegram\Commands;


use Illuminate\Support\Facades\Http;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;

class MyAptekaCommand extends Command
{
    public function handle($update)
    {
        $chatId = $update->message->chat->id;
        $userTelegramId = $update->message->from->id;

        $this->showApteka($chatId, $userTelegramId);
    }

    public function showApteka($chatId, $userTelegramId, $editMessageId = null)
    {
        \Log::info('MyAptekaCommand called', ['user' => $userTelegramId]);
        $response = Http::get("{$this->apiUrl}/user-medicines", ['telegram_id' => $userTelegramId]);

        if ($response->failed() || empty($response->json('data'))) {
            $text = "Ваша аптечка пуста 😔\nДобавьте лекарства через поиск или вручную.";
            $this->sendOrEditMessage($chatId, $text, null, $editMessageId);
            return;
        }

        $items = $response->json('data');

        $text = "🗑 *Ваша аптечка*:\n\n";
        $inlineKeyboard = Keyboard::make()->inline();

        foreach ($items as $index => $item) {
            // Получаем название из details, которое теперь точно будет в массиве
            $name = $item['details']['trade_name'] ?? 'Неизвестно';
            $quantity = $item['quantity'] ?? 0;
            $notes = $item['notes'] ? " — {$item['notes']}" : '';

            $reminderIcon = ($item['has_reminder'] ?? false) ? ' ⏰' : '';

            $text .= ($index + 1) . ". {$name}{$reminderIcon} {$notes}\n";

            $inlineKeyboard->row([
                Keyboard::inlineButton([
                    'text' => "🗑 Удалить {$name}",
                    'callback_data' => 'delete_med_' . $item['id']
                ]),
                Keyboard::inlineButton([
                    'text' => '⏰ Напоминание',
                    'callback_data' => 'set_reminder_' . $item['id']
                ])
            ]);
        }

        $this->sendOrEditMessage($chatId, $text, $inlineKeyboard, $editMessageId);

        // Сохраняем message_id для обновления при удалении
        if (!$editMessageId) {
            // Если это новое сообщение — сохраняем ID
            // Но поскольку мы не знаем ID здесь, сохраним флаг, что аптечка открыта
            Cache::put("apteka_open_{$userTelegramId}", true, 600);
        }
    }

    protected function sendOrEditMessage($chatId, $text, $replyMarkup = null, $editMessageId = null)
    {
        if ($editMessageId) {
            $this->telegram->editMessageText([
                'chat_id' => $chatId,
                'message_id' => $editMessageId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => $replyMarkup
            ]);
        } else {
            $sent = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_markup' => $replyMarkup
            ]);
            // Сохраняем message_id для будущих обновлений
            $messageId = $sent->message_id;
            Cache::put("apteka_message_id_{$sent->chat->id}", $messageId, 600);
        }
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
