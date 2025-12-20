<?php

namespace App\Http\Controllers\Telegram\Commands;

use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ListCommand extends Command
{
    public function handle($update)
    {
        $chatId = $update->message->chat->id;
        $userTelegramId = $update->message->from->id;

        $this->showPage($chatId, $userTelegramId, 1);
    }

    public function showPage($chatId, $userTelegramId, $page = 1, $messageId = null)
    {
        $perPage = 10;
        $response = Http::get("{$this->apiUrl}/medicines", ['page' => $page, 'per_page' => $perPage]);

        if ($response->failed() || empty($response->json('data'))) {
            $this->sendMessage($chatId, "Справочник пуст 😔");
            return;
        }

        $data = $response->json();
        $medicines = $data['data'];
        $currentPage = $data['current_page'];
        $lastPage = $data['last_page'];

        // Сохраняем результаты для добавления (опционально, если нужно)
        Cache::put("list_results_{$userTelegramId}", $medicines, 600);

        $text = "📋 Справочник лекарств (страница {$currentPage} из {$lastPage})\n\n";
        foreach ($medicines as $index => $med) {
            $label = $med['trade_name'];
            if (!empty($med['dosage'])) $label .= " ({$med['dosage']})";
            $text .= ($index + 1) . ". {$label}\n";
        }
        $text .= "\nВыберите для добавления или листайте:";

        $inlineKeyboard = Keyboard::make()->inline();

// Кнопки добавления лекарств — по одной в строке
        foreach ($medicines as $med) {
            $label = $med['trade_name'];
            if (!empty($med['dosage'])) $label .= " ({$med['dosage']})";

            $inlineKeyboard->row([  // ← Оберни в массив!
                Keyboard::inlineButton([
                    'text' => "➕ {$label}",
                    'callback_data' => 'add_med_' . $med['id']
                ])
            ]);
        }

// Строка пагинации
        $paginationRow = [];
        if ($currentPage > 1) {
            $paginationRow[] = Keyboard::inlineButton([
                'text' => '◀️ Назад',
                'callback_data' => 'list_prev_' . ($currentPage - 1)
            ]);
        }
        if ($currentPage < $lastPage) {
            $paginationRow[] = Keyboard::inlineButton([
                'text' => 'Далее ▶️',
                'callback_data' => 'list_next_' . ($currentPage + 1)
            ]);
        }

        if (!empty($paginationRow)) {
            $inlineKeyboard->row($paginationRow);  // ← Можно без spread, просто массив
        }

// Кнопка закрытия
        $inlineKeyboard->row([
            Keyboard::inlineButton([
                'text' => '❌ Закрыть',
                'callback_data' => 'list_close'
            ])
        ]);

        if ($messageId) {
            // Обновляем существующее сообщение (для "одного виджета")
            $this->telegram->editMessageText([
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'reply_markup' => $inlineKeyboard,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            // Первое сообщение
            $sent = $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => $inlineKeyboard,
                'parse_mode' => 'Markdown'
            ]);
            // Сохраняем message_id для будущих обновлений
            Cache::put("list_message_{$userTelegramId}", $sent->message_id, 600);
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
