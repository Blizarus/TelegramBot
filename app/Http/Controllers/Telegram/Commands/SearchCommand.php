<?php

namespace App\Http\Controllers\Telegram\Commands;


use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SearchCommand extends Command
{
    public function handle($update)
    {
        $chatId = $update->message->chat->id;
        $userTelegramId = $update->message->from->id;

        Cache::put("state_{$userTelegramId}", 'waiting_search', 600);
        $this->sendMessage($chatId, "🔍 Введите название лекарства или действующее вещество:");
    }

    public function performSearch($chatId, $query, $userTelegramId)
    {
        $response = Http::get("{$this->apiUrl}/medicines/search", ['q' => $query]);

        if ($response->failed() || empty($response->json('data'))) {
            $this->sendMessage($chatId, "Ничего не найдено 😔\nПопробуйте другой запрос или добавьте вручную.");
            Cache::forget("state_{$userTelegramId}");
            return;
        }

        $medicines = $response->json('data');
        Cache::put("search_results_{$userTelegramId}", $medicines, 600);

        $inlineKeyboard = Keyboard::make()->inline();

        foreach ($medicines as $med) {
            $label = $med['trade_name'];
            if (!empty($med['dosage'])) $label .= " ({$med['dosage']})";

            $inlineKeyboard->row([  // ← Вот здесь было без массива!
                Keyboard::inlineButton([
                    'text' => "➕ {$label}",
                    'callback_data' => 'add_med_' . $med['id']
                ])
            ]);
        }

        $inlineKeyboard->row([
            Keyboard::inlineButton([
                'text' => '❌ Отмена',
                'callback_data' => 'cancel_search'
            ])
        ]);

        $this->sendMessage(
            $chatId,
            "🔍 Найдено: " . count($medicines) . " вариантов\nНажмите на нужное, чтобы добавить:",
            $inlineKeyboard
        );

        Cache::forget("state_{$userTelegramId}");
    }

    protected function sendMessage($chatId, $text, $replyMarkup = null)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
            'reply_markup' => $replyMarkup
        ]);
    }
}
