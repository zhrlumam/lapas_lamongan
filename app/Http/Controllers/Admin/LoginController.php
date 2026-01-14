<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Berita;
use App\Models\WargaBinaan;
use App\Models\SurveyKepuasan;
use App\Models\Kunjungan;

class LoginController extends Controller
{
    public function dashboard()
    {
        $totalBerita = Berita::count();
        $hunian = WargaBinaan::latest('tanggal_update')->first() ?: (object)['total_penghuni' => 0];
        $totalWbp = $hunian->total_penghuni;
        $survey = SurveyKepuasan::where('is_active', 1)->first();
        $skorIkm = $survey?->skor_ikm ?? 0;
        $pengunjungHariIni = Kunjungan::whereDate('tanggal_kunjungan', date('Y-m-d'))->count();

        // Analytics Data: Visits last 7 days (Optimized - Single Query)
        $visitStats = Kunjungan::selectRaw('DATE(tanggal_kunjungan) as date, COUNT(*) as total')
            ->where('tanggal_kunjungan', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $visitData = [];
        $visitLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $visitLabels[] = now()->subDays($i)->format('d M');
            $visitData[] = $visitStats[$date] ?? 0;
        }

        // Analytics Data: Complaints Status
        $complaintStats = \App\Models\Pengaduan::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Recent Complaints for the table
        $recentComplaints = \App\Models\Pengaduan::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBerita', 'totalWbp', 'skorIkm', 'pengunjungHariIni', 
            'visitData', 'visitLabels', 'complaintStats', 'recentComplaints'
        ));
    }

    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
