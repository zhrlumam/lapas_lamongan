<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatIntegrasi;
use App\Models\Integrasi;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Laravel\Socialite\Facades\Socialite;
use App\Services\ILovePdfService;

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
        $user = Auth::guard('penjamin')->user();
        if (!$user->nik || !$user->nama_wbp) {
            return redirect()->route('integrasi.complete_profile');
        }

        // Fetch recent submissions for this penjamin
        $recentSubmissions = Integrasi::where('nik_penjamin', $user->nik)
                                      ->latest()
                                      ->take(10) // Increased from 5 to 10
                                      ->get();

        // Status counts untuk notifikasi
        $statusCounts = [
            'pending' => Integrasi::where('nik_penjamin', $user->nik)->where('status', 'pending')->count(),
            'approved' => Integrasi::where('nik_penjamin', $user->nik)->where('status', 'approved')->count(),
            'rejected' => Integrasi::where('nik_penjamin', $user->nik)->where('status', 'rejected')->count(),
        ];

        // Cek apakah ada pengajuan yang baru disetujui/ditolak dalam 7 hari terakhir
        $recentStatusChanges = Integrasi::where('nik_penjamin', $user->nik)
                                        ->whereIn('status', ['approved', 'rejected'])
                                        ->where('updated_at', '>=', now()->subDays(7))
                                        ->latest('updated_at')
                                        ->get();

        return view('frontend.integrasi.dashboard', compact('recentSubmissions', 'statusCounts', 'recentStatusChanges'));
    }

    public function completeProfile()
    {
        return view('frontend.integrasi.complete_profile');
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:users,nik,' . Auth::guard('penjamin')->id(),
            'nama_wbp' => 'required|string|max:255',
        ]);

        // SECURITY FIX: Sanitasi input
        $nik = preg_replace('/[^0-9]/', '', $request->nik); // Hanya angka
        $namaWbp = strip_tags($request->nama_wbp); // Hapus HTML tags

        $user = Auth::guard('penjamin')->user();
        $user->update([
            'nik' => $nik,
            'nama_wbp' => $namaWbp,
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
        
        $type = $request->query('type', 'CB'); // Default CB
        
        return view('frontend.integrasi.form', compact('type'));
    }

    /**
     * Map full program name to database abbreviation
     */
    private function mapJenisProgram($jenisLayanan)
    {
        $mapping = [
            'Pembebasan Bersyarat' => 'PB',
            'Cuti Bersyarat' => 'CB',
            'Cuti Menjelang Bebas' => 'CMB',
            'Asimilasi Kerja Sosial' => 'AKS',
            'Asimilasi Pihak Ketiga' => 'APK',
        ];

        return $mapping[$jenisLayanan] ?? 'CB'; // Default to CB if not found
    }

    /**
     * Submit Pengajuan (TAHAP 1: Hanya Submit Data, Tidak Download)
     * User mengisi form dan submit → Data tersimpan dengan status 'pending'
     * → Menunggu verifikasi Admin
     */
    public function submitPengajuan(Request $request)
    {
        try {
            // SECURITY FIX: Validasi dan ambil hanya data yang diperlukan
            $validated = $request->validate([
                'nama_penjamin' => 'required|string|max:255',
                'nik_penjamin' => 'required|digits:16',
                'umur_penjamin' => 'required|integer|min:17|max:100',
                'pekerjaan_penjamin' => 'required|string|max:255',
                'hubungan_wbp' => 'required|string|max:100',
                'alamat_penjamin' => 'required|string|max:500',
                'no_hp_penjamin' => 'required|regex:/^[0-9+]{10,15}$/',
                'nama_wbp' => 'required|string|max:255',
                'umur_wbp' => 'required|integer|min:17|max:100',
                'jenis_layanan' => 'required|string',
                'perkara' => 'required|string|max:500',
                'pernyataan' => 'required',
            ]);

            // SECURITY FIX: Sanitasi semua input untuk mencegah XSS
            $requestData = [
                'nama_penjamin' => ucwords(strtolower(strip_tags($validated['nama_penjamin']))),
                'nik_penjamin' => preg_replace('/[^0-9]/', '', $validated['nik_penjamin']),
                'umur_penjamin' => (int)$validated['umur_penjamin'],
                'pekerjaan_penjamin' => ucwords(strtolower(strip_tags($validated['pekerjaan_penjamin']))),
                'hubungan_wbp' => ucwords(strtolower(strip_tags($validated['hubungan_wbp']))),
                'alamat_penjamin' => ucwords(strtolower(strip_tags($validated['alamat_penjamin']))),
                'no_hp_penjamin' => preg_replace('/[^0-9+]/', '', $validated['no_hp_penjamin']),
                'nama_wbp' => ucwords(strtolower(strip_tags($validated['nama_wbp']))),
                'umur_wbp' => (int)$validated['umur_wbp'],
                'jenis_layanan' => strip_tags($validated['jenis_layanan']),
                'perkara' => ucwords(strtolower(strip_tags($validated['perkara']))),
            ];

            // Map jenis layanan to database enum value
            $jenisProgram = $this->mapJenisProgram($requestData['jenis_layanan']);

            // PERBAIKAN: Cek duplikasi - cegah double submit dalam 5 menit
            $existingRecord = Integrasi::where('nik_penjamin', $requestData['nik_penjamin'])
                ->where('nama_wbp', $requestData['nama_wbp'])
                ->where('jenis_program', $jenisProgram)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->first();

            if ($existingRecord) {
                // Data sudah ada dalam 5 menit terakhir, redirect tanpa simpan lagi
                return redirect()->route('integrasi.dashboard')
                    ->with('info', 'Pengajuan dengan data yang sama sudah dikirim sebelumnya. Silakan cek di dashboard.');
            }

            // Simpan ke database dengan status 'pending' - LENGKAP SEMUA FIELD
            Integrasi::create([
                'nama_penjamin'      => $requestData['nama_penjamin'],
                'nik_penjamin'       => $requestData['nik_penjamin'],
                'umur_penjamin'      => $requestData['umur_penjamin'],
                'pekerjaan_penjamin' => $requestData['pekerjaan_penjamin'],
                'hubungan_penjamin'  => $requestData['hubungan_wbp'],
                'alamat_penjamin'    => $requestData['alamat_penjamin'],
                'telepon_penjamin'   => $requestData['no_hp_penjamin'],
                'nama_wbp'           => $requestData['nama_wbp'],
                'umur_wbp'           => $requestData['umur_wbp'],
                'perkara'            => $requestData['perkara'],
                'jenis_program'      => $jenisProgram,
                'tanggal_pengajuan'  => date('Y-m-d'),
                'status'             => 'pending',
            ]);

            // PERUBAHAN: Tidak generate PDF, langsung redirect ke dashboard
            return redirect()->route('integrasi.dashboard')
                ->with('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi Admin. Anda akan dapat mengunduh PDF setelah pengajuan disetujui.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation error - tampilkan ke user
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // SECURITY FIX: Log detail error, tapi tampilkan pesan generic ke user
            \Log::error('Submit Pengajuan Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->except(['_token'])));
            
            return back()->with('error', 'Terjadi kesalahan saat mengirim pengajuan. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.');
        }
    }

    /**
     * DEPRECATED: Method lama generatePDF untuk DOCX
     * Masih dipertahankan untuk backward compatibility
     */
    public function generatePDF(Request $request)
    {
        try {
            // SECURITY FIX: Validasi dan ambil hanya data yang diperlukan
            $validated = $request->validate([
                'nama_penjamin' => 'required|string|max:255',
                'nik_penjamin' => 'required|digits:16',
                'umur_penjamin' => 'required|integer|min:17|max:100',
                'pekerjaan_penjamin' => 'required|string|max:255',
                'hubungan_wbp' => 'required|string|max:100',
                'alamat_penjamin' => 'required|string|max:500',
                'no_hp_penjamin' => 'required|regex:/^[0-9+]{10,15}$/',
                'nama_wbp' => 'required|string|max:255',
                'umur_wbp' => 'required|integer|min:17|max:100',
                'jenis_layanan' => 'required|string',
                'perkara' => 'required|string|max:500',
            ]);

            // SECURITY FIX: Sanitasi semua input untuk mencegah XSS
            $requestData = [
                'nama_penjamin' => ucwords(strtolower(strip_tags($validated['nama_penjamin']))),
                'nik_penjamin' => preg_replace('/[^0-9]/', '', $validated['nik_penjamin']),
                'umur_penjamin' => (int)$validated['umur_penjamin'],
                'pekerjaan_penjamin' => ucwords(strtolower(strip_tags($validated['pekerjaan_penjamin']))),
                'hubungan_wbp' => ucwords(strtolower(strip_tags($validated['hubungan_wbp']))),
                'alamat_penjamin' => ucwords(strtolower(strip_tags($validated['alamat_penjamin']))),
                'no_hp_penjamin' => preg_replace('/[^0-9+]/', '', $validated['no_hp_penjamin']),
                'nama_wbp' => ucwords(strtolower(strip_tags($validated['nama_wbp']))),
                'umur_wbp' => (int)$validated['umur_wbp'],
                'jenis_layanan' => strip_tags($validated['jenis_layanan']),
                'perkara' => ucwords(strtolower(strip_tags($validated['perkara']))),
            ];

            // Map jenis layanan to database enum value
            $jenisProgram = $this->mapJenisProgram($requestData['jenis_layanan']);

            // Simpan ke database agar bisa dikelola Admin - LENGKAP SEMUA FIELD
            Integrasi::create([
                'nama_penjamin'      => $requestData['nama_penjamin'],
                'nik_penjamin'       => $requestData['nik_penjamin'],
                'umur_penjamin'      => $requestData['umur_penjamin'],
                'pekerjaan_penjamin' => $requestData['pekerjaan_penjamin'],
                'hubungan_penjamin'  => $requestData['hubungan_wbp'],
                'alamat_penjamin'    => $requestData['alamat_penjamin'],
                'telepon_penjamin'   => $requestData['no_hp_penjamin'],
                'nama_wbp'           => $requestData['nama_wbp'],
                'umur_wbp'           => $requestData['umur_wbp'],
                'perkara'            => $requestData['perkara'],
                'jenis_program'      => $jenisProgram,
                'tanggal_pengajuan'  => date('Y-m-d'),
                'status'             => 'pending',
            ]);

            // [PHPWord Template Processor Strategy]
            // Path to Word template
            $templatePath = storage_path('app/templates/template_jaminan.docx');

            if (!file_exists($templatePath)) {
                \Log::error("Template Word tidak ditemukan di: " . $templatePath);
                return back()->with('error', 'File template_jaminan.docx tidak ditemukan. Silakan hubungi administrator.');
            }

            // Initialize Template Processor
            $templateProcessor = new TemplateProcessor($templatePath);

            // Prepare data mapping for ${placeholder} replacement
            $data = [
                'nama_penjamin'      => $requestData['nama_penjamin'] ?? '-',
                'umur_penjamin'      => $requestData['umur_penjamin'] ?? '-',
                'pekerjaan_penjamin' => $requestData['pekerjaan_penjamin'] ?? '-',
                'hubungan_penjamin'  => $requestData['hubungan_wbp'] ?? '-',
                'alamat_penjamin'    => $requestData['alamat_penjamin'] ?? '-',
                'telp_penjamin'      => $requestData['no_hp_penjamin'] ?? '-',
                'nama_napi'          => $requestData['nama_wbp'] ?? '-',
                'umur_napi'          => $requestData['umur_wbp'] ?? '-',
                'program'            => $requestData['jenis_layanan'] ?? 'Pembebasan Bersyarat',
                'tanggal_surat'      => \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'),
            ];

            // DEBUG: Log data yang akan di-replace
            \Log::info('PHPWord Template - Data yang akan di-replace:', $data);
            \Log::info('PHPWord Template - Template path:', ['path' => $templatePath]);

            // Replace all placeholders
            foreach ($data as $key => $value) {
                $templateProcessor->setValue($key, $value);
                \Log::debug("PHPWord - Replaced: \${$key} = {$value}");
            }

            // Save temporary file
            $namaFile = 'Surat_Jaminan_' . str_replace(' ', '_', $data['nama_napi']) . '.docx';
            $tempFilePath = storage_path('app/public/' . $namaFile);
            $templateProcessor->saveAs($tempFilePath);

            // Download and delete after send
            return response()->download($tempFilePath)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            // SECURITY FIX: Log detail error, tapi tampilkan pesan generic ke user
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->except(['_token'])));
            
            return back()->with('error', 'Terjadi kesalahan saat membuat dokumen. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.');
        }
    }

    /**
     * Generate PDF Format (bukan DOCX)
     * Menggunakan DOMPDF untuk convert ke PDF
     */
    public function generatePDFFormat(Request $request)
    {
        try {
            // SECURITY FIX: Validasi dan ambil hanya data yang diperlukan
            $validated = $request->validate([
                'nama_penjamin' => 'required|string|max:255',
                'nik_penjamin' => 'required|digits:16',
                'umur_penjamin' => 'required|integer|min:17|max:100',
                'pekerjaan_penjamin' => 'required|string|max:255',
                'hubungan_wbp' => 'required|string|max:100',
                'alamat_penjamin' => 'required|string|max:500',
                'no_hp_penjamin' => 'required|regex:/^[0-9+]{10,15}$/',
                'nama_wbp' => 'required|string|max:255',
                'umur_wbp' => 'required|integer|min:17|max:100',
                'jenis_layanan' => 'required|string',
                'perkara' => 'required|string|max:500',
            ]);

            // SECURITY FIX: Sanitasi semua input untuk mencegah XSS
            $requestData = [
                'nama_penjamin' => ucwords(strtolower(strip_tags($validated['nama_penjamin']))),
                'nik_penjamin' => preg_replace('/[^0-9]/', '', $validated['nik_penjamin']),
                'umur_penjamin' => (int)$validated['umur_penjamin'],
                'pekerjaan_penjamin' => ucwords(strtolower(strip_tags($validated['pekerjaan_penjamin']))),
                'hubungan_wbp' => ucwords(strtolower(strip_tags($validated['hubungan_wbp']))),
                'alamat_penjamin' => ucwords(strtolower(strip_tags($validated['alamat_penjamin']))),
                'no_hp_penjamin' => preg_replace('/[^0-9+]/', '', $validated['no_hp_penjamin']),
                'nama_wbp' => ucwords(strtolower(strip_tags($validated['nama_wbp']))),
                'umur_wbp' => (int)$validated['umur_wbp'],
                'jenis_layanan' => strip_tags($validated['jenis_layanan']),
                'perkara' => ucwords(strtolower(strip_tags($validated['perkara']))),
            ];

            // Map jenis layanan to database enum value
            $jenisProgram = $this->mapJenisProgram($requestData['jenis_layanan']);

            // Simpan ke database
            Integrasi::create([
                'nama_penjamin'     => $requestData['nama_penjamin'],
                'nik_penjamin'      => $requestData['nik_penjamin'],
                'alamat_penjamin'   => $requestData['alamat_penjamin'],
                'telepon_penjamin'  => $requestData['no_hp_penjamin'],
                'nama_wbp'          => $requestData['nama_wbp'],
                'perkara'           => $requestData['perkara'],
                'jenis_program'     => $jenisProgram,
                'tanggal_pengajuan' => date('Y-m-d'),
                'status'            => 'pending',
            ]);

            // Prepare data untuk view
            $data = [
                'nama_penjamin'      => $requestData['nama_penjamin'] ?? '-',
                'umur_penjamin'      => $requestData['umur_penjamin'] ?? '-',
                'pekerjaan_penjamin' => $requestData['pekerjaan_penjamin'] ?? '-',
                'hubungan_penjamin'  => $requestData['hubungan_wbp'] ?? '-',
                'alamat_penjamin'    => $requestData['alamat_penjamin'] ?? '-',
                'telp_penjamin'      => $requestData['no_hp_penjamin'] ?? '-',
                'nama_napi'          => $requestData['nama_wbp'] ?? '-',
                'umur_napi'          => $requestData['umur_wbp'] ?? '-',
                'program'            => $requestData['jenis_layanan'] ?? 'Pembebasan Bersyarat',
                'tanggal_surat'      => \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'),
            ];

            // Generate PDF menggunakan DOMPDF
            $pdf = Pdf::loadView('pdf.surat_jaminan', $data);
            
            // Set paper size dan orientation
            $pdf->setPaper('F4', 'portrait');
            
            // Download PDF
            $namaFile = 'Surat_Jaminan_' . str_replace(' ', '_', $data['nama_napi']) . '.pdf';
            return $pdf->download($namaFile);
            
        } catch (\Exception $e) {
            // SECURITY FIX: Log detail error, tapi tampilkan pesan generic ke user
            \Log::error('PDF Format Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->except(['_token'])));
            
            return back()->with('error', 'Terjadi kesalahan saat membuat dokumen PDF. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.');
        }
    }

    /**
     * Download PDF untuk Pengajuan yang Sudah Approved (TAHAP 3)
     * Hanya bisa download jika:
     * 1. Pengajuan milik user yang login
     * 2. Status = 'approved'
     * 
     * Flow: Generate DOCX → Convert ke PDF via iLovePDF → Download PDF
     */
    public function downloadApprovedPDF($id)
    {
        try {
            // Cari pengajuan berdasarkan ID
            $pengajuan = Integrasi::findOrFail($id);
            
            // SECURITY: Cek ownership - hanya pemilik yang bisa download
            $user = Auth::guard('penjamin')->user();
            if ($pengajuan->nik_penjamin !== $user->nik) {
                abort(403, 'Anda tidak memiliki akses untuk mengunduh dokumen ini.');
            }
            
            // Cek status - hanya yang approved yang bisa download
            if ($pengajuan->status !== 'approved') {
                return redirect()->route('integrasi.dashboard')
                    ->with('error', 'Dokumen hanya dapat diunduh setelah pengajuan disetujui oleh Admin. Status saat ini: ' . $pengajuan->status);
            }
            
            // [STEP 1: Generate DOCX dari Template]
            $templatePath = storage_path('app/templates/template_jaminan.docx');

            if (!file_exists($templatePath)) {
                \Log::error("Template Word tidak ditemukan di: " . $templatePath);
                return redirect()->route('integrasi.dashboard')
                    ->with('error', 'File template_jaminan.docx tidak ditemukan. Silakan hubungi administrator.');
            }

            // Initialize Template Processor
            $templateProcessor = new TemplateProcessor($templatePath);

            // Map jenis program dari abbreviation ke full name
            $programMapping = [
                'PB' => 'Pembebasan Bersyarat',
                'CB' => 'Cuti Bersyarat',
                'CMB' => 'Cuti Menjelang Bebas',
                'AKS' => 'Asimilasi Kerja Sosial',
                'APK' => 'Asimilasi Pihak Ketiga',
            ];
            $program = $programMapping[$pengajuan->jenis_program] ?? $pengajuan->jenis_program;

            // Prepare data mapping for ${placeholder} replacement
            $data = [
                'nama_penjamin'      => ucwords(strtolower($pengajuan->nama_penjamin ?? '-')),
                'umur_penjamin'      => $pengajuan->umur_penjamin ?? '-',
                'pekerjaan_penjamin' => ucwords(strtolower($pengajuan->pekerjaan_penjamin ?? '-')),
                'hubungan_penjamin'  => ucwords(strtolower($pengajuan->hubungan_penjamin ?? '-')),
                'alamat_penjamin'    => ucwords(strtolower($pengajuan->alamat_penjamin ?? '-')),
                'telp_penjamin'      => $pengajuan->telepon_penjamin ?? '-',
                'nama_napi'          => ucwords(strtolower($pengajuan->nama_wbp ?? '-')),
                'umur_napi'          => $pengajuan->umur_wbp ?? '-',
                'program'            => $program,
                'tanggal_surat'      => \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'),
            ];

            // Replace all placeholders
            foreach ($data as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            // Save DOCX temporary file
            $namaFileDocx = 'Surat_Jaminan_' . str_replace(' ', '_', $data['nama_napi']) . '_' . time() . '.docx';
            $tempDocxPath = storage_path('app/public/' . $namaFileDocx);
            $templateProcessor->saveAs($tempDocxPath);

            \Log::info("DOCX generated: {$tempDocxPath}");

            // [STEP 2: Convert DOCX ke PDF menggunakan iLovePDF]
            try {
                $pdfService = new ILovePdfService();
                $namaFilePdf = 'Surat_Jaminan_' . str_replace(' ', '_', $data['nama_napi']) . '.pdf';
                
                \Log::info("Starting PDF conversion with iLovePDF...");
                $pdfPath = $pdfService->convertWordToPdf($tempDocxPath, $namaFilePdf);
                
                \Log::info("PDF conversion successful: {$pdfPath}");

                // Hapus file DOCX temporary
                if (file_exists($tempDocxPath)) {
                    unlink($tempDocxPath);
                    \Log::info("Temporary DOCX deleted: {$tempDocxPath}");
                }

                // Download PDF dan hapus setelah download
                return response()->download($pdfPath)->deleteFileAfterSend(true);

            } catch (\Exception $pdfError) {
                // Jika konversi PDF gagal, fallback ke download DOCX
                \Log::error("PDF Conversion Error: " . $pdfError->getMessage());
                \Log::warning("Fallback: Downloading DOCX instead of PDF");

                return response()->download($tempDocxPath, str_replace('.docx', '_WORD.docx', $namaFileDocx))
                    ->deleteFileAfterSend(true);
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('integrasi.dashboard')
                ->with('error', 'Pengajuan tidak ditemukan.');
                
        } catch (\Exception $e) {
            \Log::error('Download Approved Document Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Pengajuan ID: ' . $id);
            
            return redirect()->route('integrasi.dashboard')
                ->with('error', 'Terjadi kesalahan saat mengunduh dokumen. Silakan coba lagi atau hubungi administrator.');
        }
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
