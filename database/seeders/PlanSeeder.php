<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Plan::create([
            'name' => 'Plano Básico',
            'slug' => 'basico',
            'monthly_price' => 49.90,
            'yearly_price' => 499.00,
            'max_users' => 10,
            'max_entities' => 5,
            'features' => ['Suporte básico', 'Acesso limitado'],
            'is_active' => true,
        ]);
    }
}
