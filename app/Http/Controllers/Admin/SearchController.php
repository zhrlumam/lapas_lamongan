<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WargaBinaan;
use App\Models\Kunjungan;
use App\Models\Integrasi;
use App\Models\Pengaduan;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $results = [];

        // Search WBP
        $wbp = WargaBinaan::where('nama_wbp', 'LIKE', "%{$query}%")
            ->orWhere('perkara', 'LIKE', "%{$query}%")
            ->limit(3)->get();
        foreach ($wbp as $item) {
            $results[] = [
                'type' => 'Warga Binaan',
                'title' => $item->nama_wbp,
                'sub' => $item->perkara,
                'url' => route('admin.hunian.index') . '?q=' . $item->nama_wbp,
                'icon' => 'users'
            ];
        }

        // Search Kunjungan
        $kunjungan = Kunjungan::where('nama_pengunjung', 'LIKE', "%{$query}%")
            ->orWhere('nama_wbp', 'LIKE', "%{$query}%")
            ->orWhere('nik', 'LIKE', "%{$query}%")
            ->limit(3)->get();
        foreach ($kunjungan as $item) {
            $results[] = [
                'type' => 'Kunjungan',
                'title' => $item->nama_pengunjung,
                'sub' => 'Ke: ' . $item->nama_wbp,
                'url' => route('admin.kunjungan.index') . '?date=' . $item->tanggal_kunjungan,
                'icon' => 'calendar'
            ];
        }

        // Search Integrasi
        $integrasi = Integrasi::where('nama_penjamin', 'LIKE', "%{$query}%")
            ->orWhere('nama_wbp', 'LIKE', "%{$query}%")
            ->limit(3)->get();
        foreach ($integrasi as $item) {
            $results[] = [
                'type' => 'Integrasi',
                'title' => $item->nama_penjamin,
                'sub' => 'WBP: ' . $item->nama_wbp,
                'url' => route('admin.integrasi.index'),
                'icon' => 'file-check'
            ];
        }

        return response()->json($results);
    }
}
