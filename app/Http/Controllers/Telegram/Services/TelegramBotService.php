<?php

namespace App\Http\Controllers\Telegram\Services;


use App\Http\Controllers\Telegram\Commands\AddCustomCommand;
use App\Http\Controllers\Telegram\Commands\CallbackCommand;
use App\Http\Controllers\Telegram\Commands\ListCommand;
use App\Http\Controllers\Telegram\Commands\MyAptekaCommand;
use App\Http\Controllers\Telegram\Commands\SearchCommand;
use App\Http\Controllers\Telegram\Commands\SetReminderCommand;
use App\Http\Controllers\Telegram\Commands\StartCommand;
use Telegram\Bot\Objects\Update;
use Illuminate\Support\Facades\Cache;

class TelegramBotService
{
    protected $commands;

    public function __construct()
    {
        $this->commands = [
            'search' => new SearchCommand(),
            'my_apteka' => new MyAptekaCommand(),
            'add_custom' => new AddCustomCommand(),
            'callback' => new CallbackCommand(),
            'list' => new ListCommand(),
        ];
    }

    public function handleUpdate(Update $update)
    {
        if ($update->has('callback_query')) {
            $this->commands['callback']->handle($update);
            return;
        }

        if (!$update->has('message')) {
            return;
        }

        $message = $update->message;
        $text = $message->text ?? '';
        $chatId = $message->chat->id;
        $userTelegramId = $message->from->id;
        $state = Cache::get("state_{$userTelegramId}");
        $reminder = Cache::get("reminder_step_{$userTelegramId}");

        if (in_array($reminder, ['waiting_time', 'waiting_frequency', 'waiting_days', 'waiting_start_date', 'waiting_end_date'])) {
            (new SetReminderCommand())->processStep($chatId, $userTelegramId, $text);
            return;
        }

        // Обработка состояний
        if ($state === 'waiting_search' && $text !== '🔍 Найти лекарство') {
            $this->commands['search']->performSearch($message->chat->id, $text, $userTelegramId);
            return;
        }

        if ($state === 'waiting_custom_name') {
            $this->commands['add_custom']->complete($message->chat->id, $userTelegramId, $text);
            return;
        }

        // Обработка команд
        switch ($text) {
            case '/start':
                (new StartCommand())->handle($update);
                break;

            case '🔍 Найти лекарство':
                $this->commands['search']->handle($update);
                break;

            case '🗑 Моя аптечка':
                $this->commands['my_apteka']->handle($update);
                break;

            case '➕ Добавить лекарство вручную':
                $this->commands['add_custom']->handle($update);
                break;

            case '📋 Все лекарства':
                $this->commands['list']->handle($update);
                break;
        }
    }
}
