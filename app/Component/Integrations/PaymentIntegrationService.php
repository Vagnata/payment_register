<?php

namespace App\Component\Integrations;

use App\Domain\Exceptions\AuthorizationIntegrationServiceException;
use App\Domain\Exceptions\TransactionDeniedException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PaymentIntegrationService
{
    const AUTHORIZATION_TRANSACTION_URI = '8fafdd68-a090-496f-8c9a-3442cf30dae6';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authorizeTransaction(): bool
    {
        try {
            $response = $this->client->request('GET', self::AUTHORIZATION_TRANSACTION_URI);
        } catch (\Exception $exception) {
            Log::error('AUTHORIZATION INTEGRATION ERROR: ' . $exception->getMessage());
            throw new AuthorizationIntegrationServiceException();
        }

        return $this->validateAuthorizationResponse($response);
    }

    private function validateAuthorizationResponse($response): bool
    {
        $body = json_decode($response->getBody()->__toString(), true);

        if (!array_key_exists('message', $body) || $body['message'] != 'Autorizado') {
            throw new TransactionDeniedException();
        }

        return true;
    }
}
