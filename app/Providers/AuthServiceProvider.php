<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Tenant::class => \App\Policies\TenantPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access-tenant', function (User $user, $tenant) {
            // Permite Company para retrocompatibilidade, mas prioriza Tenant
            if ($tenant instanceof \App\Models\Tenant) {
                return $user->tenants()->where('tenants.id', $tenant->id)->exists();
            }
            // Se for Company, verifica se o usuário pertence à company
            if ($tenant instanceof \App\Models\Company) {
                return $user->companies()->where('companies.id', $tenant->id)->exists();
            }
            return false;
        });
    }
}
