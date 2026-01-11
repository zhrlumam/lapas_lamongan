<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
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
        } elseif ($date) {
            $query->whereDate('tanggal_kunjungan', $date);
        }

        if ($status) {
            $query->where('status', $status);
        }
        
        $data = $query->orderBy('nomor_antrian', 'asc')
                      ->orderBy('id', 'desc')
                      ->paginate(20);

        // Stats - disesuaikan dengan status MASUK / KELUAR
        $stats = [
            'total' => Kunjungan::whereDate('tanggal_kunjungan', $date)->count(),
            'masuk' => Kunjungan::whereDate('tanggal_kunjungan', $date)->where('status', 'masuk')->count(),
            'keluar' => Kunjungan::whereDate('tanggal_kunjungan', $date)->where('status', 'keluar')->count(),
            'pending' => Kunjungan::whereDate('tanggal_kunjungan', $date)->where('status', 'approved')->count(),
        ];
                         
        return view('admin.kunjungan.index', compact('data', 'date', 'search', 'status', 'stats'));
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
        
        Kunjungan::whereIn('id', $ids)->each(function($item) {
            $item->pengunjung()->delete();
            $item->delete();
        });

        return back()->with('success', count($ids) . ' Data kunjungan berhasil dihapus.');
    }
}
