<?php

namespace App\Http\Controllers\Telegram\Commands;

use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;

class StartCommand extends Command
{
    public function handle($update)
    {
        $chatId = $update->message->chat->id;
        $userTelegramId = $update->message->from->id;
        $firstName = $update->message->from->first_name ?? 'Пользователь';

        // Регистрация пользователя
        \Illuminate\Support\Facades\Http::post("{$this->apiUrl}/register", [
            'telegram_id' => $userTelegramId,
            'name' => $firstName
        ]);

        $keyboard = Keyboard::make()->row([
            Keyboard::button('🔍 Найти лекарство'),
            Keyboard::button('🗑 Моя аптечка')
        ])->row([
            Keyboard::button('➕ Добавить лекарство вручную'),
            Keyboard::button('📋 Все лекарства')
        ]);

        $this->sendMessage($chatId, "Привет, {$firstName}! 👋\nЯ бот для напоминаний о приёме лекарств 💊\nВыберите действие:", $keyboard);

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
