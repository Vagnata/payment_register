<?php

namespace App\Providers;

use App\Domain\Models\Transaction;
use App\Domain\Observers\TransactionObserver;
use App\Domain\Repositories\Contracts\NotificationRepositoryInterface;
use App\Domain\Repositories\Contracts\TransactionRepositoryInterface;
use App\Domain\Repositories\Contracts\UserRepositoryInterface;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;
use App\Domain\Repositories\Eloquent\NotificationRepository;
use App\Domain\Repositories\Eloquent\TransactionRepository;
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
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::observe(TransactionObserver::class);
    }
}
