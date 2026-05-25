<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $plans = Plan::factory()->count(3)->create();
        $users = User::factory()->count(5)->create();
        foreach ($users as $user) {
            $tenant = Tenant::factory()->create();
            $tenant->users()->attach($user->id, ['role' => 'owner']);
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plans->random()->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'active' => true,
                'trial' => true,
            ]);
        }
    }
}
