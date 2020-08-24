<?php

namespace App\Providers;

use App\Component\Integrations\NotificationIntegrationService;
use App\Component\Integrations\PaymentIntegrationService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class IntegrationProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PaymentIntegrationService::class, function ($app) {
            return new PaymentIntegrationService(new Client(config('services.transaction_integration')));
        });

        $this->app->singleton(NotificationIntegrationService::class, function ($app) {
            return new NotificationIntegrationService(new Client(config('services.transaction_integration')));
        });
    }
}
