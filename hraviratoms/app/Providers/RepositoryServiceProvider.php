<?php

namespace App\Providers;

use App\Repositories\EloquentInvitationRepository;
use App\Repositories\InvitationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            InvitationRepositoryInterface::class,
            EloquentInvitationRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
