<?php

namespace App\Console\Commands;

use App\Component\Integrations\NotificationIntegrationService;
use App\Domain\Enuns\NotificationStatusEnum;
use App\Domain\Models\Notification;
use App\Domain\Services\NotificationService;
use Illuminate\Console\Command;

class NotificationSenderCommand extends Command
{
    protected $signature = 'payment_register:send_notifications';
    protected $description = 'Send awaiting notifications to users';
    private $notificationService;
    private $notificationIntegrationService;

    public function __construct(
        NotificationService $notificationService,
        NotificationIntegrationService $notificationIntegrationService
    ) {
        parent::__construct();
        $this->notificationService            = $notificationService;
        $this->notificationIntegrationService = $notificationIntegrationService;
    }

    public function handle()
    {
        $notifications = $this->notificationService->findAwaitingNotifications();

        $notifications->each(function (Notification $notification) {
            try {
                if ($this->notificationIntegrationService->notifyUser()) {
                    $this->notificationService->updateNotification(
                        $notification,
                        ['notification_status' => NotificationStatusEnum::SENT]
                    );
                }
            } catch (\Exception $exception) {
                //continue
            }
        });

        $this->info(trans('messages.notification.update_successfully'));
    }
}
