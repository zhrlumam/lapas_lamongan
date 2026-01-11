<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatIntegrasi;
use App\Models\Integrasi;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Laravel\Socialite\Facades\Socialite;

class IntegrasiController extends Controller
{
    public function index(Request $request)
    {
        // Handle Google OAuth Callback (Redirected here due to Console Config)
        if ($request->has('code')) {
            return app(\App\Http\Controllers\GoogleController::class)->handleGoogleCallback();
        }

        if (Auth::guard('penjamin')->check()) {
            return redirect()->route('integrasi.dashboard');
        }
        return view('frontend.integrasi.login');
    }

    public function handleLogin(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'wbp' => 'required',
        ]);

        $user = \App\Models\User::where('nik', $request->nik)
                                ->where('nama_wbp', $request->wbp)
                                ->first();

        if ($user) {
            Auth::guard('penjamin')->login($user);
            return redirect()->route('integrasi.dashboard');
        }
        
        $totalUsers = \App\Models\User::count();
        if ($totalUsers == 0) {
            return back()->with('error', 'Sistem belum memiliki data penjamin. Silakan klik "Masuk dengan Google" untuk mendaftarkan akun Anda pertama kali.');
        }

        return back()->with('error', 'Kombinasi NIK dan Nama WBP tidak ditemukan. Pastikan Anda sudah melengkapi profil setelah login Google sebelumnya.');
    }

    // Modern Google Login via Socialite handled by GoogleController (or here if moved)
    
    public function dashboard()
    {
        if (!Auth::guard('penjamin')->check()) {
            return redirect()->route('integrasi.login');
        }

        $user = Auth::guard('penjamin')->user();
        if (!$user->nik || !$user->nama_wbp) {
            return redirect()->route('integrasi.complete_profile');
        }

        return view('frontend.integrasi.dashboard');
    }

    public function completeProfile()
    {
        if (!Auth::guard('penjamin')->check()) {
            return redirect()->route('integrasi.login');
        }
        return view('frontend.integrasi.complete_profile');
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:users,nik,' . Auth::guard('penjamin')->id(),
            'nama_wbp' => 'required|string|max:255',
        ]);

        $user = Auth::guard('penjamin')->user();
        $user->update([
            'nik' => $request->nik,
            'nama_wbp' => $request->nama_wbp,
        ]);

        return redirect()->route('integrasi.dashboard')->with('success', 'Profil berhasil dilengkapi. Sekarang Anda bisa login menggunakan NIK Anda.');
    }

    public function logout()
    {
        Auth::guard('penjamin')->logout();
        return redirect()->route('integrasi.login');
    }

    public function form(Request $request)
    {
        if (!Auth::guard('penjamin')->check()) {
            return redirect()->route('integrasi.login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $type = $request->query('type', 'CB'); // Default CB
        
        return view('frontend.integrasi.form', compact('type'));
    }

    public function generatePDF(Request $request)
    {
        $data = $request->all();
        
        // Check if GD is installed to decide on image rendering in View if needed, 
        // though strictly DomPDF might check this itself.
        // We pass a flag 'has_gd' just in case we need logic in blade, 
        // but 'extension_loaded' works in blade too.

        // Cek duplikasi data dalam 24 jam terakhir
        $existingData = Integrasi::where('nik_penjamin', $data['nik_penjamin'] ?? '-')
            ->where('nama_wbp', $data['nama_wbp'] ?? '-')
            ->where('jenis_program', $data['program'] ?? 'CB')
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($existingData) {
            return back()->with('error', 'Anda sudah mengajukan permohonan yang sama dalam 24 jam terakhir. Silakan tunggu atau hubungi petugas untuk informasi lebih lanjut.');
        }

        // Simpan ke database agar bisa dikelola Admin
        Integrasi::create([
            'nama_penjamin'     => $data['nama_penjamin'] ?? '-',
            'nik_penjamin'      => $data['nik_penjamin'] ?? '-',
            'alamat_penjamin'   => $data['alamat_penjamin'] ?? '-',
            'telepon_penjamin'  => $data['telepon_penjamin'] ?? '-',
            'nama_wbp'          => $data['nama_wbp'] ?? '-',
            'perkara'           => $data['perkara'] ?? '-',
            'jenis_program'     => $data['program'] ?? 'CB',
            'tanggal_pengajuan' => date('Y-m-d'),
            'status'            => 'pending',
        ]);

        $pdf = Pdf::loadView('pdf.surat_jaminan', compact('data'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('Surat_Jaminan_Integrasi.pdf');
    }

    public function downloadTemplate()
    {
        $path = public_path('assets/PENJAMIN KOSONGAN.doc');
        if (file_exists($path)) {
            return response()->download($path);
        }
        return back()->with('error', 'File template tidak ditemukan.');
    }
}
