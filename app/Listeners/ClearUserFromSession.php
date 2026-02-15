<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class ClearUserFromSession
{
    /**
     * Handle the event.
     * Clear user data from session after logout.
     */
    public function handle(Logout $event): void
    {
        session()->forget('user');
    }
}
