<?php

namespace App\Providers;

use App\Listeners\ClearUserFromSession;
use App\Listeners\StoreUserInSession;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners for session management
        Event::listen(Login::class, StoreUserInSession::class);
        Event::listen(Logout::class, ClearUserFromSession::class);
    }
}
