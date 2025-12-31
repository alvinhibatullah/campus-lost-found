<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /* =======================
         * SUMMARY STATISTICS
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
         * TOP KATEGORI (FIXED COLUMN NAME)
         * ======================= */
        $locations = DB::table('lost_items')
            // PERBAIKAN DISINI: Ganti 'kategori_id' jadi 'category_id'
            ->join('categories', 'lost_items.category_id', '=', 'categories.id') 
            ->select('categories.nama as location', DB::raw('COUNT(*) as total'))
            ->groupBy('categories.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /* =======================
         * RECENT REPORTS
         * ======================= */
        try {
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
        } catch (\Exception $e) {
            $reports = collect([]); 
        }

        return view('dashboard.index', compact(
            'totalLost',
            'totalFound',
            'resolvedClaims',
            'locations',
            'reports'
        ));
    }
}