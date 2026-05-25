<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Plan;
use App\Models\User;

class ExampleTenantsSeeder extends Seeder
{
    public function run(): void
    {
        $plan = Plan::first();
        $users = User::all();
        if (!$plan || $users->isEmpty()) return;

        $examples = [
            ['name' => 'Tenant Alpha', 'slug' => 'tenant-alpha'],
            ['name' => 'Tenant Beta', 'slug' => 'tenant-beta'],
            ['name' => 'Tenant Gamma', 'slug' => 'tenant-gamma'],
        ];

        foreach ($examples as $ex) {
            $company = Company::firstOrCreate([
                'slug' => $ex['slug']
            ], [
                'name' => $ex['name'],
                'address' => '',
                'postal_code' => '',
                'city' => '',
            ]);
            foreach ($users as $user) {
                $company->users()->syncWithoutDetaching([$user->id => ['role' => 'owner', 'is_owner' => true]]);
            }
            $company->subscription()->updateOrCreate(
                ['company_id' => $company->id],
                ['plan_id' => $plan->id, 'status' => 'active']
            );
        }
    }
}
