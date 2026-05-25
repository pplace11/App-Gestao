<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'tenant_id' => null, // set in seeder
            'plan_id' => null, // set in seeder
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'active' => true,
            'trial' => true,
        ];
    }
}
