<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\WelcomeEmail;
use Spatie\Permission\Models\Role;

class UserObserver
{
    public function created(User $user): void
    {
        Role::findOrCreate('user', 'web');

        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        // Send welcome email notification
        $user->notify(new WelcomeEmail($user));
    }
}
