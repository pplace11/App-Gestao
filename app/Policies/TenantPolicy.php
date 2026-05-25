<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function view(User $user, Tenant $tenant): bool
    {
        return $user->tenants()->where('tenants.id', $tenant->id)->exists();
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->tenants()->where('tenants.id', $tenant->id)
            ->wherePivot('role', 'owner')->exists();
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->tenants()->where('tenants.id', $tenant->id)
            ->wherePivot('role', 'owner')->exists();
    }

    public function create(User $user): bool
    {
        return true; // Qualquer user autenticado pode criar tenant
    }
}
