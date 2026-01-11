<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WargaBinaan;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WBPExport;
use App\Exports\KunjunganExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function exportWBP()
    {
        try {
            $data = WargaBinaan::latest('tanggal_update')->take(30)->get();
            $profil = \App\Models\ProfilLapas::first();
            
            $pdf = Pdf::loadView('pdf.laporan_wbp', compact('data', 'profil'))
                      ->setPaper('a4', 'portrait');
                      
            return $pdf->download('Laporan_Harian_WBP_'.date('d-m-Y').'.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating WBP PDF: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat laporan PDF. Silakan coba lagi.');
        }
    }

    public function exportKunjungan(Request $request)
    {
        try {
            $validated = $request->validate([
                'bulan' => 'nullable|integer|min:1|max:12',
                'tahun' => 'nullable|integer|min:2020|max:2100',
            ]);

            $bulan = $validated['bulan'] ?? date('m');
            $tahun = $validated['tahun'] ?? date('Y');
            
            $data = Kunjungan::whereMonth('tanggal_kunjungan', $bulan)
                             ->whereYear('tanggal_kunjungan', $tahun)
                             ->orderBy('tanggal_kunjungan', 'asc')
                             ->get();
                             
            $profil = \App\Models\ProfilLapas::first();
            
            $pdf = Pdf::loadView('pdf.laporan_kunjungan', compact('data', 'profil', 'bulan', 'tahun'))
                      ->setPaper('a4', 'landscape');
                      
            return $pdf->download('Laporan_Kunjungan_'.$bulan.'_'.$tahun.'.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating Kunjungan PDF: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat laporan PDF. Silakan coba lagi.');
        }
    }

    public function exportWBPExcel()
    {
        try {
            return Excel::download(new WBPExport, 'Laporan_WBP_'.date('d-m-Y').'.xlsx');
        } catch (\Exception $e) {
            Log::error('Error generating WBP Excel: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat laporan Excel. Silakan coba lagi.');
        }
    }

    public function exportKunjunganExcel(Request $request)
    {
        try {
            $validated = $request->validate([
                'bulan' => 'nullable|integer|min:1|max:12',
                'tahun' => 'nullable|integer|min:2020|max:2100',
            ]);

            $bulan = $validated['bulan'] ?? date('m');
            $tahun = $validated['tahun'] ?? date('Y');
            
            return Excel::download(new KunjunganExport($bulan, $tahun), 'Rekap_Kunjungan_'.$bulan.'_'.$tahun.'.xlsx');
        } catch (\Exception $e) {
            Log::error('Error generating Kunjungan Excel: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat laporan Excel. Silakan coba lagi.');
        }
    }

    public function traffic()
    {
        try {
            $total_visitors = \App\Models\VisitorLog::count();
            $unique_visitors = \App\Models\VisitorLog::distinct('ip_address')->count();
            $today_visitors = \App\Models\VisitorLog::whereDate('visited_at', today())->count();
            
            // FIX: Gunakan parameter binding untuk keamanan
            $daily_traffic = \App\Models\VisitorLog::select(
                    DB::raw('DATE(visited_at) as date'), 
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy(DB::raw('DATE(visited_at)'))
                ->orderBy('date', 'desc')
                ->take(30)
                ->get();

            return view('admin.laporan.traffic', compact('total_visitors', 'unique_visitors', 'today_visitors', 'daily_traffic'));
        } catch (\Exception $e) {
            Log::error('Error loading traffic data: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data traffic. Silakan coba lagi.');
        }
    }
}
