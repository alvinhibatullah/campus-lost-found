<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        // SETTING PERMANEN: Paksa aplikasi menggunakan Waktu Jakarta (WIB)
        // Ini memastikan data yang disimpan dan ditampilkan sinkron.
        date_default_timezone_set('Asia/Jakarta');
        config(['app.timezone' => 'Asia/Jakarta']);
        Carbon::setLocale('id');
    }

    public function index()
    {
        // 1. Data Reports (Laporan Internal)
        $reports = DB::table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')
            ->latest()
            ->get();

        // 2. Statistik
        // Internal
        $myLost   = DB::table('reports')->where('status', 'Searching')->count();
        $myFound  = DB::table('reports')->where('status', 'Found')->count();
        $myClaims = DB::table('reports')->where('status', 'Closed')->count();

        // Bayu (Lost Items)
        $bayuLost   = DB::table('lost_items')->where('status', 'Searching')->count();
        $bayuFound  = DB::table('lost_items')->where('status', 'Found')->count();
        $bayuClaims = DB::table('lost_items')->where('status', 'Closed')->count();

        // Arenko (Found Items)
        $arenkoUnclaimed = DB::table('found_items')->where('status', 'Unclaimed')->count();
        $arenkoClaimed   = DB::table('found_items')->where('status', 'Claimed')->count();
        $arenkoClosed    = DB::table('found_items')->where('status', 'Closed')->count();

        // Dawai (Claims)
        $dawaiTaken = DB::table('claims')->where('status', 'taken')->count();

        // Total Gabungan
        $lostCount   = $myLost + $bayuLost; 
        $foundCount  = $myFound + $bayuFound + $arenkoUnclaimed; 
        $claimsCount = $myClaims + $bayuClaims + $arenkoClaimed + $arenkoClosed + $dawaiTaken;

        // 3. Live Feed (Gabungan 3 Sumber)
        $recentLost = DB::table('lost_items')
            ->join('categories', 'lost_items.category_id', '=', 'categories.id')
            ->select('lost_items.nama_barang', 'lost_items.created_at', 'lost_items.status', 'categories.nama as kategori')
            ->orderBy('lost_items.created_at', 'desc')
            ->limit(3)
            ->get();

        $recentFound = DB::table('found_items')
            ->select('nama_barang', 'created_at', 'status', DB::raw('"Barang Temuan" as kategori'))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $recentClaims = DB::table('claims')
            ->select('item_name as nama_barang', 'created_at', 'status', 'category as kategori')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Merge & Sort
        $recentItems = $recentLost->merge($recentFound)->merge($recentClaims)->sortByDesc('created_at')->take(6);

        // 4. Chart Data
        $chartLabels = ['Barang Hilang', 'Ditemukan (Unclaimed)', 'Selesai/Diklaim'];
        $chartValues = [$lostCount, $foundCount, $claimsCount];

        return view('reports.index', compact(
            'reports', 'lostCount', 'foundCount', 'claimsCount',
            'recentItems', 'chartLabels', 'chartValues'
        ));
    }

    public function create() 
    { 
        return view('reports.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'incident_date' => 'required|date',
            'report_type_id' => 'required',
            'status' => 'required'
        ]);

        try {
            DB::table('reports')->insert([
                'title' => $request->title,
                'incident_date' => $request->incident_date,
                'report_type_id' => $request->report_type_id,
                'status' => $request->status,
                'user_id' => auth()->id() ?? null, 
                'created_at' => now(), // Otomatis mengikuti timezone Jakarta
                'updated_at' => now(),
            ]);
            return redirect()->route('reports.index')->with('success', 'Report berhasil dibuat.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function destroy($id) 
    {
        DB::table('reports')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Report dihapus.');
    }

    public function exportPdf($id) 
    {
        $report = DB::table('reports')->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')->where('reports.id', $id)->first();
        $pdf = Pdf::loadView('reports.pdf', compact('report'));
        return $pdf->download('report-'.$id.'.pdf');
    }

    public function print($id) 
    {
        $report = DB::table('reports')->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')->where('reports.id', $id)->first();
        return view('reports.print', compact('report'));
    }
}