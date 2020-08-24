<?php

namespace Tests\Domain\Repositories\Eloquent;

use App\Domain\Enuns\NotificationStatusEnum;
use App\Domain\Models\Notification;
use App\Domain\Repositories\Eloquent\NotificationRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class NotificationRepositoryTest extends TestCase
{
    /** @var NotificationRepository */
    private $notificationRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationRepository= new NotificationRepository();
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf(NotificationRepository::class, $this->notificationRepository);
    }

    /**
     * @test
     */
    public function shouldReturnACollectionWithAwaitingNotifications()
    {
        factory(Notification::class, 3)
            ->create(['notification_status' => NotificationStatusEnum::AWAITING]);

        $collection = $this->notificationRepository->findAwaitingNotifications();


        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(3, $collection);
    }
}
