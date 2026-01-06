<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function index(): JsonResponse
    {
        $stats = [
            'customers' => [
                'total' => Customer::count(),
                'new_this_month' => Customer::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'new_this_week' => Customer::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            ],
            'users' => [
                'total' => User::count(),
            ],
            'recent_customers' => Customer::latest()
                ->take(5)
                ->get(['id', 'first_name', 'last_name', 'email', 'created_at'])
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'full_name' => $customer->first_name . ' ' . $customer->last_name,
                        'email' => $customer->email,
                        'created_at' => $customer->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
        ];

        return response()->json($stats);
    }
}
