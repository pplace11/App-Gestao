<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $name = $this->faker->word . ' Plan';
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->randomNumber(3),
            'user_limit' => $this->faker->numberBetween(1, 100),
            'features' => ['feature1', 'feature2'],
            'price' => $this->faker->randomFloat(2, 0, 100),
            'trial_days' => $this->faker->numberBetween(7, 30),
        ];
    }
}
