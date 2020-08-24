<?php

namespace App\Component\Integrations;

use App\Domain\Exceptions\NotificationIntegrationServiceException;
use App\Domain\Exceptions\NotificationNotSentException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NotificationIntegrationService
{
    const TRANSACTION_NOTIFICATION_URI = 'b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function notifyUser(): bool
    {
        try {
            $response = $this->client->request('GET', self::TRANSACTION_NOTIFICATION_URI);
        } catch (\Exception $exception) {
            Log::error('NOTIFICATION INTEGRATION ERROR: ' . $exception->getMessage());
            throw new NotificationIntegrationServiceException();
        }

        return $this->validateNotificationResponse($response);
    }

    private function validateNotificationResponse($response): bool
    {
        $body = json_decode($response->getBody()->__toString(), true);

        if (!array_key_exists('message', $body) || $body['message'] != 'Enviado') {
            throw new NotificationNotSentException();
        }

        return true;
    }
}
