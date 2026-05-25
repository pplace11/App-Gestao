<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $activeTenantId = (int) (session('tenant_id') ?? 0);
        $tenants = $user->tenants()->orderBy('name', 'asc')->get(['tenants.id', 'tenants.name', 'tenants.slug']);
        // Se não houver tenant ativo ou não pertence ao user, definir o primeiro como ativo
        if (!$activeTenantId || !$tenants->contains('id', $activeTenantId)) {
            $activeTenantId = $tenants->first()?->id ?? 0;
            if ($activeTenantId) {
                session(['tenant_id' => $activeTenantId]);
            }
        }
        $tenants = $tenants->map(function (Tenant $tenant) use ($activeTenantId) {
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'is_active' => $tenant->id === $activeTenantId,
                'role' => $tenant->pivot?->role,
            ];
        });
        return response()->json([
            'active_tenant_id' => $activeTenantId,
            'tenants' => $tenants,
        ]);
    }

    public function current(Request $request): JsonResponse
    {
        $tenantId = session('tenant_id');
        $tenant = $user->tenants()->where('tenants.id', $tenantId)->first();
        if (!$tenant) {
            return response()->json(['message' => 'Nenhum tenant ativo.'], 404);
        }
        return response()->json($tenant);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'settings' => 'nullable|array',
        ]);
        $tenant = Tenant::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'settings' => $validated['settings'] ?? [],
        ]);
        $tenant->users()->attach($user->id, ['role' => 'owner']);
        $this->createTrialSubscription($tenant);
        session(['tenant_id' => $tenant->id]);
        return response()->json($tenant, 201);
    }

    public function switch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => 'required|integer|exists:tenants,id',
        ]);
        $user = $request->user();
        $tenant = $user->tenants()->where('tenants.id', $validated['tenant_id'])->first();
        if (!$tenant) {
            return response()->json(['message' => 'Sem permissao para este tenant.'], 403);
        }
        session(['tenant_id' => $tenant->id]);
        return response()->json([
            'message' => 'Tenant ativo atualizado.',
            'tenant' => $tenant,
        ]);
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $tenantId = session('tenant_id');
        $tenant = $user->tenants()->where('tenants.id', $tenantId)->first();
        if (!$tenant) {
            return response()->json(['message' => 'Tenant ativo nao encontrado.'], 404);
        }
        $validated = $request->validate([
            'preferences' => 'required|array',
        ]);
        $settings = $tenant->settings ?? [];
        $settings['preferences'] = array_merge($settings['preferences'] ?? [], $validated['preferences']);
        $tenant->update(['settings' => $settings]);
        return response()->json([
            'message' => 'Preferencias do tenant atualizadas.',
            'settings' => $tenant->settings,
        ]);
    }

    public function updateOnboardingChecklist(Request $request): JsonResponse
    {
        $tenantId = session('tenant_id');
        $tenant = $user->tenants()->where('tenants.id', $tenantId)->first();
        if (!$tenant) {
            return response()->json(['message' => 'Tenant ativo nao encontrado.'], 404);
        }
        $validated = $request->validate([
            'onboarding' => 'required|array',
        ]);
        $settings = $tenant->settings ?? [];
        $settings['onboarding'] = array_merge($settings['onboarding'] ?? [], $validated['onboarding']);
        $tenant->update(['settings' => $settings]);
        return response()->json([
            'message' => 'Checklist de onboarding atualizada.',
            'settings' => $tenant->settings,
        ]);
    }

    private function createTrialSubscription(Tenant $tenant): void
    {
        if (!Schema::hasTable('subscriptions')) {
            return;
        }
        $plan = Plan::query()->orderBy('price', 'asc')->first();
        Subscription::query()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'plan_id' => $plan?->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'active' => true,
                'trial' => true,
            ]
        );
    }
}
