<?php

namespace Tests\Domain\Services;

use App\Domain\Models\Notification;
use App\Domain\Models\Transaction;
use App\Domain\Repositories\Eloquent\NotificationRepository;
use App\Domain\Services\NotificationService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    /** @var NotificationService */
    private $notificationService;

    /** @var LegacyMockInterface */
    private $notificationRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationRepositoryMock = \Mockery::mock(NotificationRepository::class);
        $this->notificationService        = new NotificationService($this->notificationRepositoryMock);
    }

    /**
     * @test
     */
    public function shouldAddOneNotification()
    {
        $transaction = factory(Transaction::class)->create();
        $fixture     = factory(Notification::class)->create(['user_id' => $transaction->payeeWallet->user->id]);
        $this->notificationRepositoryMock->shouldReceive('save')->withAnyArgs()->andReturn($fixture);

        $notification = $this->notificationService->addNotification($transaction);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($transaction->payeeWallet->user->id, $notification->user_id);
    }
}
