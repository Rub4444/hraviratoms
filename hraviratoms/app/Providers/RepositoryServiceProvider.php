<?php

namespace App\Providers;

use App\Repositories\EloquentInvitationRepository;
use App\Repositories\InvitationRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\InvitationRsvpRepositoryInterface;
use App\Repositories\EloquentInvitationRsvpRepository;

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

        $this->app->bind(
            InvitationRsvpRepositoryInterface::class,
            EloquentInvitationRsvpRepository::class
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
