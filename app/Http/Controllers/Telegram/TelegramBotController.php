<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Telegram\Services\TelegramBotService;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramBotController extends Controller
{
    protected $telegram;
    protected $botService;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->botService = new TelegramBotService();
    }

    public function webhook(Request $request)
    {
        // Вот правильный способ получить обновление как объект
        $update = $this->telegram->getWebhookUpdate();

        $this->botService->handleUpdate($update);

        return 'ok';
    }

    public function setWebhook()
    {
        $url = env('TELEGRAM_BOT_WEBHOOK_URL');
        $response = $this->telegram->setWebhook(['url' => $url]);

        return $response ? 'Webhook установлен!' : 'Ошибка';
    }
}
