<?php

namespace Tests\Components\Integrations;

use App\Component\Integrations\NotificationIntegrationService;
use App\Domain\Exceptions\NotificationIntegrationServiceException;
use App\Domain\Exceptions\NotificationNotSentException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use Tests\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use Symfony\Component\HttpFoundation\Response;

class NotificationIntegrationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotifyOneUser()
    {
        $request                        = new MockHandler([
            new Psr7\Response(Response::HTTP_OK, [], Psr7\stream_for(json_encode(['message' => 'Enviado'])))
        ]);
        $client                         = new Client(['handler' => HandlerStack::create($request)]);
        $notificationIntegrationService = new NotificationIntegrationService($client);

        $authorization = $notificationIntegrationService->notifyUser();

        $this->assertTrue($authorization);
    }

    /**
     * @test
     */
    public function shouldThrowNotificationIntegrationServiceException()
    {
        $request            = new MockHandler([
            new Psr7\Response(Response::HTTP_INTERNAL_SERVER_ERROR, [], Psr7\stream_for(json_encode([])))
        ]);
        $client                         = new Client(['handler' => HandlerStack::create($request)]);
        $notificationIntegrationService = new NotificationIntegrationService($client);

        $this->expectExceptionMessage('Serviço de notificação de transações está indisponível');
        $this->expectException(NotificationIntegrationServiceException::class);

        $notificationIntegrationService->notifyUser();
    }

    /**
     * @test
     */
    public function shouldThrowNotificationNotSentException()
    {
        $request            = new MockHandler([
            new Psr7\Response(Response::HTTP_OK, [], Psr7\stream_for(json_encode(['message' => 'Não Enviado'])))
        ]);
        $client                         = new Client(['handler' => HandlerStack::create($request)]);
        $notificationIntegrationService = new NotificationIntegrationService($client);

        $this->expectExceptionMessage('Notificação não enviada');
        $this->expectException(NotificationNotSentException::class);

        $notificationIntegrationService->notifyUser();
    }
}
