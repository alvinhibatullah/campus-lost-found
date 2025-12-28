<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;

class ClaimVerificationController extends Controller
{

    public function queue()
    {
        $claims = Claim::with(['user', 'item'])
            ->whereIn('status', ['pending', 'need_more_proof'])
            ->latest()
            ->paginate(10);

        return view('admin.claims.queue', compact('claims'));
    }

    
    public function verify(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'action' => 'required|in:approved,rejected,need_more_proof',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $claim->status = $validated['action'];
        $claim->admin_note = $validated['admin_note'] ?? null;
        $claim->save();


        return redirect()->route('admin.claims.queue')->with('success', 'Status klaim berhasil diperbarui.');
    }
}