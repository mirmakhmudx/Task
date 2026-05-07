<?php

namespace App\Providers\Auth;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('admin-panel', function ($user) {
            return $user->canAccessAdminPanel();
        });

        Gate::define('manage-users', function ($user) {
            return $user->canManageUsers();
        });
    }
}
