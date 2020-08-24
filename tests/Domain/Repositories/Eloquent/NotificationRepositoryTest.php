<?php

namespace Tests\Domain\Repositories\Eloquent;

use App\Domain\Repositories\Eloquent\NotificationRepository;
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

}
