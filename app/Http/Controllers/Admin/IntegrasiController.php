<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Integrasi;
use Illuminate\Http\Request;

class IntegrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Integrasi::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by program
        if ($request->filled('program')) {
            $query->where('jenis_program', $request->program);
        }

        // Search by name or NIK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_wbp', 'like', "%{$search}%")
                  ->orWhere('nama_penjamin', 'like', "%{$search}%")
                  ->orWhere('nik_penjamin', 'like', "%{$search}%");
            });
        }

        $data = $query->latest()->paginate(10);

        // Statistics for Dashboard
        $stats = [
            'total'     => Integrasi::count(),
            'pending'   => Integrasi::where('status', 'pending')->count(),
            'approved'  => Integrasi::where('status', 'approved')->count(),
            'today'     => Integrasi::whereDate('created_at', today())->count(),
        ];

        return view('admin.integrasi.index', compact('data', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'alasan' => 'nullable|string'
        ]);

        $item = Integrasi::findOrFail($id);
        
        // We can reuse 'file_surat' or add a new column for 'alasan' if needed.
        // For now, let's just update the status.
        $item->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status integrasi berhasil diperbarui.');
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:approved,rejected'
        ]);

        Integrasi::whereIn('id', $request->ids)->update([
            'status' => $request->status,
        ]);

        return back()->with('success', count($request->ids) . ' data berhasil diperbarui secara massal.');
    }

    public function destroy($id)
    {
        Integrasi::findOrFail($id)->delete();
        return back()->with('success', 'Data integrasi berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        $count = Integrasi::whereIn('id', $request->ids)->delete();
        
        return back()->with('success', $count . ' data berhasil dihapus secara massal.');
    }

    public function show($id)
    {
        $item = Integrasi::findOrFail($id);
        return view('admin.integrasi.show', compact('item'));
    }
}
