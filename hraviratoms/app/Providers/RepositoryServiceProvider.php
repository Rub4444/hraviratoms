<?php

namespace App\Providers;

use App\Repositories\EloquentInvitationRepository;
use App\Repositories\InvitationRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\InvitationRsvpRepositoryInterface;
use App\Repositories\EloquentInvitationRsvpRepository;
use App\Repositories\InvitationTemplateRepositoryInterface;
use App\Repositories\EloquentInvitationTemplateRepository;
use App\Repositories\GuestRepositoryInterface;
use App\Repositories\EloquentGuestRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\EloquentUserRepository;


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

        $this->app->bind(
            InvitationTemplateRepositoryInterface::class,
            EloquentInvitationTemplateRepository::class
        );

        $this->app->bind(
            GuestRepositoryInterface::class,
            EloquentGuestRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
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
