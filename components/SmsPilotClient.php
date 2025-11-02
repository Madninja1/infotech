<?php
namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;
use Yii;


class SmsPilotClient extends Component
{
    /** @var string Точка входа API */
    public string $endpoint = 'https://smspilot.ru/api.php';

    /** @var string API-ключ (может быть "эмулятор" для теста) */
    public string $apiKey = 'эмулятор';

    public function send(string $phone, string $message): bool
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if (!$phone) {
            Yii::warning('SmsPilot: передан пустой номер телефона.');
            return false;
        }

        try {
            $client = new Client();

            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->endpoint)
                ->setData([
                    'send'   => $message,
                    'to'     => $phone,
                    'apikey' => $this->apiKey,
                    'format' => 'json',
                ])
                ->send();

            if (!$response->isOk) {
                Yii::error("SmsPilot: ошибка запроса ({$response->statusCode}) — {$response->content}");
                return false;
            }

            Yii::info("SmsPilot: сообщение успешно отправлено на {$phone}. Ключ: {$this->apiKey}");
            return true;

        } catch (\Throwable $e) {
            Yii::error('SmsPilot: исключение при отправке SMS — ' . $e->getMessage());
            return false;
        }
    }
}
