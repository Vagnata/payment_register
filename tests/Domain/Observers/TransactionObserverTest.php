<?php

namespace Tests\Domain\Services;

use App\Domain\Models\Notification;
use App\Domain\Models\Transaction;
use App\Domain\Observers\TransactionObserver;
use App\Domain\Services\NotificationService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;

class TransactionObserverTest extends TestCase
{
    /** @var TransactionObserver */
    private $transactionObserver;

    /** @var LegacyMockInterface */
    private $notificationServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationServiceMock = \Mockery::mock(NotificationService::class);
        $this->transactionObserver     = new TransactionObserver($this->notificationServiceMock);
    }

    /**
     * @test
     */
    public function shouldCreateNotificationByTransaction()
    {
        $transaction = factory(Transaction::class)->create();
        $fixture     = factory(Notification::class)->create();
        $this->notificationServiceMock
            ->shouldReceive('addNotification')
            ->with($transaction)
            ->andReturn($fixture);

        $this->transactionObserver->created($transaction);

        $notification = Notification::first();
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($transaction->payeeWallet->user->id, $notification->user_id);
    }
}
