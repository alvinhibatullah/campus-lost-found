<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $reports = DB::table('reports')->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        DB::table('reports')->insert([
            'title' => $request->title,
            'report_type_id' => $request->report_type_id,
            'user_id' => auth()->id() ?? 1,
            'status' => 'GENERATED',
            'created_at' => now()
        ]);

        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        DB::table('reports')->where('id', $id)->delete();
        return redirect()->back();
    }
}
