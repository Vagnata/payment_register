<?php

namespace App\Domain\Integrations;

use App\Domain\Exceptions\AuthorizationIntegrationServiceException;
use App\Domain\Exceptions\TransactionDeniedException;
use GuzzleHttp\Client;

class PaymentIntegrationService
{
    const AUTHORIZATION_TRANSACTION = '/8fafdd68-a090-496f-8c9a-3442cf30dae6';
    const MERCHANT_NOTIFICATION     = '/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authorizeTransaction(): bool
    {
        try {
            $response = $this->client->request('GET', self::AUTHORIZATION_TRANSACTION);
        } catch (\Exception $exception) {
            throw new AuthorizationIntegrationServiceException();
        }

        $body = json_decode($response->getBody()->__toString(), true);

        if (array_key_exists('message', $body) && $body['message'] == 'Autorizado') {
            return true;
        }

        throw new TransactionDeniedException();
    }
}
