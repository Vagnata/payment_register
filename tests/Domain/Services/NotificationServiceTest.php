<?php

namespace Tests\Domain\Services;

use App\Domain\Enuns\NotificationStatusEnum;
use App\Domain\Models\Notification;
use App\Domain\Models\Transaction;
use App\Domain\Repositories\Eloquent\NotificationRepository;
use App\Domain\Services\NotificationService;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * @test
     */
    public function shouldReturnAwaitingNotificationsCollection()
    {
        $fixture = new Collection();
        $fixture->add(factory(Notification::class)->create(
            ['notification_status' => NotificationStatusEnum::AWAITING]
        ));
        $fixture->add(factory(Notification::class)->create(
            ['notification_status' => NotificationStatusEnum::AWAITING]
        ));
        $this->notificationRepositoryMock
            ->shouldReceive('findAwaitingNotifications')
            ->andReturn($fixture);

        $collection = $this->notificationService->findAwaitingNotifications();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
    }

    /**
     * @test
     */
    public function shouldUpdateOneNotification()
    {
        $fixture = factory(Notification::class)
            ->create(['notification_status' => NotificationStatusEnum::SENT]);
        $data    = ['notification_status' => NotificationStatusEnum::SENT];
        $this->notificationRepositoryMock
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn($fixture);

        $notification = $this->notificationService->updateNotification($fixture, $data);

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals(NotificationStatusEnum::SENT, $notification->notification_status);
    }
}
