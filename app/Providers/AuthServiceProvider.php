<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Location;
use App\Models\User;
use App\Models\Tree;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Example: Location::class => \App\Policies\LocationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('edit-location', function (User $user, Location $location) {
            return $user->id === $location->created_by
                || (property_exists($user, 'is_admin') && $user->is_admin);
        });

        Gate::define('edit-tree', function (User $user, Tree $tree) {
            return $user->id === $tree->created_by
                || (property_exists($user, 'is_admin') && $user->is_admin);
        });
    }
}
