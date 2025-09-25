<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\BetRepositoryInterface;
use App\Domain\Repositories\TransactionRepositoryInterface;
use App\Infrastructure\Repositories\UserRepository;
use App\Infrastructure\Repositories\EventRepository;
use App\Infrastructure\Repositories\BetRepository;
use App\Infrastructure\Repositories\TransactionRepository;
use App\Application\UseCases\RegisterUserUseCase;
use App\Application\UseCases\LoginUserUseCase;
use App\Application\UseCases\LogoutUserUseCase;
use App\Application\UseCases\GetEventsUseCase;
use App\Application\UseCases\GetEventUseCase;
use App\Application\UseCases\CreateBetUseCase;
use App\Application\UseCases\GetUserBetsUseCase;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(BetRepositoryInterface::class, BetRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        
        
        $this->app->singleton(RegisterUserUseCase::class);
        $this->app->singleton(LoginUserUseCase::class);
        $this->app->singleton(LogoutUserUseCase::class);
        $this->app->singleton(GetEventsUseCase::class);
        $this->app->singleton(GetEventUseCase::class);
        $this->app->singleton(CreateBetUseCase::class);
        $this->app->singleton(GetUserBetsUseCase::class);
    }


    public function boot(): void
    {
    }
}
