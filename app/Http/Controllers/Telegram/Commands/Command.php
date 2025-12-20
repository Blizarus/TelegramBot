<?php

namespace App\Http\Controllers\Telegram\Commands;

use Telegram\Bot\Objects\Update;

abstract class Command
{
    protected $telegram;
    protected $apiUrl;

    public function __construct()
    {
        $this->telegram = new \Telegram\Bot\Api(env('TELEGRAM_BOT_TOKEN'));
        $this->apiUrl = env('APP_URL') . '/api';
    }

    // Каждый класс команды должен реализовать этот метод
    abstract public function handle(Update $update);
}
