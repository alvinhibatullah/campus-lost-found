<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $reports = DB::table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select(
                'reports.id',
                'reports.title',
                'reports.report_type_id',
                'reports.status',
                'reports.created_at',
                'users.name as user_name'
            )
            ->orderBy('reports.created_at', 'desc')
            ->get();

        $lostCount   = $reports->where('report_type_id', 1)->count();
        $foundCount  = $reports->where('report_type_id', 2)->count();
        $claimsCount = $reports->where('report_type_id', 3)->count();

        return view('reports.index', compact(
            'reports',
            'lostCount',
            'foundCount',
            'claimsCount'
        ));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'incident_date' => 'required|date',
            'report_type_id' => 'required',
            'status' => 'required'
        ]);

        DB::table('reports')->insert([
            'title' => $request->title,
            'incident_date' => $request->incident_date,
            'report_type_id' => $request->report_type_id,
            'status' => $request->status,
            'user_id' => auth()->check() ? auth()->id() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('reports.index')
            ->with('success', 'Report berhasil dibuat');
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
            ->select(
                'reports.*',
                'users.name as user_name'
            )
            ->where('reports.id', $id)
            ->first();

        $pdf = Pdf::loadView('reports.pdf', compact('report'));

        return $pdf->download('report-'.$id.'.pdf');
    }

    public function print($id)
    {
        $report = DB::table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select(
                'reports.*',
                'users.name as user_name'
            )
            ->where('reports.id', $id)
            ->first();

        return view('reports.print', compact('report'));
    }
}
