<?php

namespace App\Providers;

use App\Components\Integrations\PaymentIntegrationService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class IntegrationProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PaymentIntegrationService::class, function ($app) {
            return new PaymentIntegrationService(new Client(config('services.payment_integration')));
        });
    }
}
