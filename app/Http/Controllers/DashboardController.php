<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /* =======================
         *  SUMMARY STATISTICS
         * ======================= */
        $totalLost = DB::table('lost_items')->count();
        $totalFound = DB::table('found_items')->count();

        $totalClaims = DB::table('claims')->count();
        $approvedClaims = DB::table('claims')
            ->where('status', 'APPROVED')
            ->count();

        $resolvedClaims = $totalClaims > 0
            ? round(($approvedClaims / $totalClaims) * 100, 2)
            : 0;

        /* =======================
         *  LOST LOCATIONS (TOP 5)
         * ======================= */
        $locations = DB::table('lost_items')
            ->select('location', DB::raw('COUNT(*) as total'))
            ->groupBy('location')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /* =======================
         *  RECENT REPORTS
         * ======================= */
        $reports = DB::table('reports')
            ->select('title as type', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'type'   => $item->type,
                    'period' => date('M Y', strtotime($item->created_at)),
                    'status' => ucfirst(strtolower($item->status))
                ];
            });

        /* =======================
         *  RETURN VIEW
         * ======================= */
        return view('dashboard.index', compact(
            'totalLost',
            'totalFound',
            'resolvedClaims',
            'locations',
            'reports'
        ));
    }
}
