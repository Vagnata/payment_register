<?php

namespace App\Providers;

use App\Domain\Repositories\Contracts\UserRepositoryInterface;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;
use App\Domain\Repositories\Eloquent\UserRepository;
use App\Domain\Repositories\Eloquent\WalletRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
