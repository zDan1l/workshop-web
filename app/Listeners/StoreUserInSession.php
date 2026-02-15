<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class StoreUserInSession
{
    /**
     * Handle the event.
     * Store user data in session after successful login.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        session([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }
}
