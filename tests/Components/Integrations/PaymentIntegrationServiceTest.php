<?php

namespace Tests\Components\Integrations;


use App\Components\Integrations\PaymentIntegrationService;
use App\Domain\Exceptions\AuthorizationIntegrationServiceException;
use App\Domain\Exceptions\TransactionDeniedException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use Tests\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use Symfony\Component\HttpFoundation\Response;

class PaymentIntegrationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAuthorizeOneTransaction()
    {
        $request            = new MockHandler([
            new Psr7\Response(Response::HTTP_OK, [], Psr7\stream_for(json_encode(['message' => 'Autorizado'])))
        ]);
        $client             = new Client(['handler' => HandlerStack::create($request)]);
        $paymentIntegration = new PaymentIntegrationService($client);

        $authorization = $paymentIntegration->authorizeTransaction();

        $this->assertTrue($authorization);
    }

    /**
     * @test
     */
    public function shouldThrowAuthorizationIntegrationServiceException()
    {
        $request            = new MockHandler([
            new Psr7\Response(Response::HTTP_INTERNAL_SERVER_ERROR, [], Psr7\stream_for(json_encode([])))
        ]);
        $client             = new Client(['handler' => HandlerStack::create($request)]);
        $paymentIntegration = new PaymentIntegrationService($client);

        $this->expectExceptionMessage('Serviço de autorização de transações está indisponível');
        $this->expectException(AuthorizationIntegrationServiceException::class);

        $paymentIntegration->authorizeTransaction();
    }

    /**
     * @test
     */
    public function shouldThrowTransactionDeniedException()
    {
        $request            = new MockHandler([
            new Psr7\Response(Response::HTTP_OK, [], Psr7\stream_for(json_encode(['message' => 'Não Autorizado'])))
        ]);
        $client             = new Client(['handler' => HandlerStack::create($request)]);
        $paymentIntegration = new PaymentIntegrationService($client);

        $this->expectExceptionMessage('Transação negada');
        $this->expectException(TransactionDeniedException::class);

        $paymentIntegration->authorizeTransaction();
    }
}
