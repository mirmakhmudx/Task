<?php

namespace App\Providers;

use App\Entity\Adverts\Advert;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('admin-panel', function (User $user) {
            return $user->canAccessAdminPanel();
        });

        Gate::define('manage-users', function (User $user) {
            return $user->canManageUsers();
        });

        Gate::define('edit-own-advert', function (User $user, Advert $advert) {
            return $advert->isOwnedBy($user);
        });

        Gate::define('show-advert', function (User $user, Advert $advert) {
            return $advert->isOwnedBy($user) || $user->canAccessAdminPanel();
        });

        Gate::define('moderate-advert', function (User $user) {
            return $user->canAccessAdminPanel();
        });
    }
}
