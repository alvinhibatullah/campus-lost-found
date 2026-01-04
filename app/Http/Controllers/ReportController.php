<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LostItem; // Panggil Model punya Bayu (pastikan model ini ada)
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $reports = DB::table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')
            ->latest()
            ->get();

        $myLost = DB::table('reports')->where('status', 'Searching')->count();
        $myFound = DB::table('reports')->where('status', 'Found')->count();
        $myClaims = DB::table('reports')->where('status', 'Closed')->count();

        $bayuLost = DB::table('lost_items')->where('status', 'Searching')->count();
        $bayuFound = DB::table('lost_items')->where('status', 'Found')->count();
        $bayuClaims = DB::table('lost_items')->where('status', 'Closed')->count();

        $lostCount = $myLost + $bayuLost;
        $foundCount = $myFound + $bayuFound;
        $claimsCount = $myClaims + $bayuClaims;

        $recentItems = DB::table('lost_items')
            ->join('categories', 'lost_items.category_id', '=', 'categories.id')
            ->select('lost_items.nama_barang', 'lost_items.created_at', 'lost_items.status', 'categories.nama as kategori')
            ->orderBy('lost_items.created_at', 'desc')
            ->limit(5)
            ->get();

        $chartData = DB::table('lost_items')
            ->join('categories', 'lost_items.category_id', '=', 'categories.id')
            ->select('categories.nama', DB::raw('count(*) as total'))
            ->groupBy('categories.nama')
            ->get();

        $chartLabels = $chartData->pluck('nama');
        $chartValues = $chartData->pluck('total');

        return view('reports.index', compact(
            'reports',
            'lostCount',
            'foundCount',
            'claimsCount',
            'recentItems',
            'chartLabels',
            'chartValues'
        ));
    }

    public function create() 
    { 
        return view('reports.create'); 
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|string',
            'incident_date' => 'required|date',
            'report_type_id' => 'required',
            'status' => 'required'
        ]);

        try {
            // 2. Insert ke Database (Pakai Try-Catch biar ketahuan kalau error)
            DB::table('reports')->insert([
                'title' => $request->title,
                'incident_date' => $request->incident_date,
                'report_type_id' => $request->report_type_id,
                'status' => $request->status,
                
                // PENTING: Pakai null coalescing operator (?? null)
                // Artinya: Kalau user login ambil ID-nya, kalau nggak login set NULL
                'user_id' => auth()->id() ?? null, 
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('reports.index')->with('success', 'Report berhasil dibuat!');

        } catch (\Exception $e) {
            // Kalau ada error database, tampilin errornya di layar biar kita tau salah dimana
            // Hapus baris dd() ini nanti kalau udah fix semua
            dd("GAGAL SIMPAN KE DATABASE: " . $e->getMessage());
            
            // Atau redirect balik dengan pesan error
            // return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function destroy($id) 
    {
        DB::table('reports')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Report dihapus');
    }

    public function exportPdf($id) 
    {
        $report = DB::table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')
            ->where('reports.id', $id)->first();
            
        $pdf = Pdf::loadView('reports.pdf', compact('report'));
        return $pdf->download('report-'.$id.'.pdf');
    }

    public function print($id) 
    {
        $report = DB::table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')
            ->where('reports.id', $id)->first();
            
        return view('reports.print', compact('report'));
    }
}