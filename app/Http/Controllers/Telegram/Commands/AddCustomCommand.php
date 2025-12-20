<?php

namespace App\Http\Controllers\Telegram\Commands;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Keyboard\Keyboard;

class AddCustomCommand extends Command
{
    public function handle($update)
    {
        $chatId = $update->message->chat->id;
        $userTelegramId = $update->message->from->id;

        Cache::put("state_{$userTelegramId}", 'waiting_custom_name', 600);
        $this->sendMessage($chatId, "➕ Введите название лекарства, которого нет в справочнике:");
    }

    public function complete($chatId, $userTelegramId, $name)
    {
        $response = Http::post("{$this->apiUrl}/custom-medicines", [
            'telegram_id' => $userTelegramId,
            'trade_name' => $name
        ]);

        if ($response->successful()) {
            $customId = $response->json('id');

            Http::post("{$this->apiUrl}/user-medicines", [
                'telegram_id' => $userTelegramId,
                'custom_medicine_id' => $customId,
                'quantity' => 30
            ]);

            $this->sendMessage($chatId, "✅ Лекарство «{$name}» добавлено в аптечку как кастомное!");
        } else {
            $this->sendMessage($chatId, "Ошибка при добавлении. Попробуйте позже.");
        }

        Cache::forget("state_{$userTelegramId}");
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
