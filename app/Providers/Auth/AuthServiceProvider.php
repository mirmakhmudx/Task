<?php

namespace App\Providers\Auth;

use App\Entity\Adverts\Advert;
use App\Models\User;
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
        Gate::define('show-advert', function (User $user, Advert $advert) {
            return $user->isAdmin() || $user->isModerator() ||$advert->user_id === $user->id;
        });

        Gate::define('moderate-advert', function (User $user, Advert $advert) {
            return $advert->user_id === $user->id;
        });
    }
}
