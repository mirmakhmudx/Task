<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Entity\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Gate::define('admin-panel', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });

        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
