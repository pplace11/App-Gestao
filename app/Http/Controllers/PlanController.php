<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::where('is_active', true)
            ->select('id', 'name', 'monthly_price', 'yearly_price', 'features', 'description')
            ->get();
        return response()->json($plans);
    }
}
