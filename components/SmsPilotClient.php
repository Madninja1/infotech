<?php
namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;

/** Клиент smspilot: поддерживает ключ "эмулятор" (без реальной отправки). */
class SmsPilotClient extends Component
{
    public string $apiKey = 'эмулятор';
    public string $endpoint = 'https://smspilot.ru/api.php';

    public function send(string $phone, string $message): bool
    {
        $phone = preg_replace('/\D+/', '', $phone); // минимальная нормализация
        $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
        $resp = $client->createRequest()
            ->setMethod('GET')->setUrl($this->endpoint)
            ->setData(['send'=>$message,'to'=>$phone,'apikey'=>$this->apiKey,'format'=>'json'])
            ->send();
        return $resp->isOk;
    }
}
