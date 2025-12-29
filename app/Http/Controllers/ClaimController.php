<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
       
        $claims = Claim::latest()->get();

        return view('claims.index', compact('claims'));
    }

    public function create()
    {
        return view('claims.create');
    }

    public function store(Request $request)
    {
        // sementara kosong dulu, nanti diisi
    }

    public function show(Claim $claim)
    {
        return view('claims.show', compact('claim'));
    }
    
}
