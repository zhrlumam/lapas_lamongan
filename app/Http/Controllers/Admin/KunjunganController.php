<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date'); 
        $search = $request->input('search');
        $status = $request->input('status');
        
        $query = Kunjungan::with('pengunjung');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pengunjung', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('nama_wbp', 'LIKE', "%{$search}%")
                  ->orWhereHas('pengunjung', function($sq) use ($search) {
                      $sq->where('nama_pengunjung', 'LIKE', "%{$search}%")
                        ->orWhere('nik_pengunjung', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        if ($date) {
            $query->whereDate('tanggal_kunjungan', $date);
        }

        if ($status) {
            $query->where('status', $status);
        }
        
        $data = $query->orderBy('tanggal_kunjungan', 'desc')
                      ->orderBy('id', 'desc')
                      ->paginate(50); // Increased for "all data" feel

        // Grouping data by date for UI
        $groupedData = $data->groupBy(function($item) {
            return $item->tanggal_kunjungan;
        });

        // Optimized Stats Calculation - Single Query
        $statsRaw = Kunjungan::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'masuk' THEN 1 ELSE 0 END) as masuk,
            SUM(CASE WHEN status = 'keluar' THEN 1 ELSE 0 END) as keluar,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as pending
        ")
        ->when($date, fn($q) => $q->whereDate('tanggal_kunjungan', $date))
        ->first();
        
        $stats = [
            'total' => $statsRaw->total ?? 0,
            'masuk' => $statsRaw->masuk ?? 0,
            'keluar' => $statsRaw->keluar ?? 0,
            'pending' => $statsRaw->pending ?? 0,
        ];
                         
        return view('admin.kunjungan.index', compact('data', 'groupedData', 'date', 'search', 'status', 'stats'));
    }

    public function updateStatus($id, $status)
    {
        $item = Kunjungan::findOrFail($id);
        
        $updateData = ['status' => $status];
        if ($status == 'masuk') {
            $updateData['check_in_at'] = now();
        } elseif ($status == 'keluar') {
            $updateData['check_out_at'] = now();
        }

        $item->update($updateData);
        return back()->with('success', 'Status kunjungan berhasil diperbarui: ' . strtoupper($status));
    }

    public function destroy($id)
    {
        $item = Kunjungan::findOrFail($id);
        $item->pengunjung()->delete();
        $item->delete();
        return back()->with('success', 'Data kunjungan berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) return back()->with('error', 'Pilih data yang akan dihapus.');
        
        // Optimized Bulk Delete with Chunking (Prevents Memory Overflow)
        Kunjungan::whereIn('id', $ids)->chunk(100, function($items) {
            foreach ($items as $item) {
                $item->pengunjung()->delete();
                $item->delete();
            }
        });

        return back()->with('success', count($ids) . ' Data kunjungan berhasil dihapus.');
    }
}
