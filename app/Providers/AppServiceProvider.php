<?php

namespace App\Providers;

use App\Listeners\ClearUserFromSession;
use App\Listeners\StoreUserInSession;
use Carbon\Carbon;
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
        // Set Carbon locale to Indonesian for translatedFormat()
        Carbon::setLocale('id');

        // Register event listeners for session management
        Event::listen(Login::class, StoreUserInSession::class);
        Event::listen(Logout::class, ClearUserFromSession::class);
    }
}
