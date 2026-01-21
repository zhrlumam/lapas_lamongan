<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Integrasi;

class SuratJaminanController extends Controller
{
    /**
     * Generate dan download Surat Jaminan Kesanggupan Keluarga dalam format PDF
     * ATURAN TEKNIS:
     * 1. Gambar Logo: Gunakan public_path() untuk DOMPDF
     * 2. Layout: Gunakan Table HTML (BUKAN Flexbox/Grid)
     * 3. Kertas: F4 (Folio) dengan ukuran point [0, 0, 609.448, 935.433]
     * 4. Font: Times New Roman
     */
    public function downloadSuratJaminan(Request $request)
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
                'nama_penjamin' => strip_tags($validated['nama_penjamin']),
                'nik_penjamin' => preg_replace('/[^0-9]/', '', $validated['nik_penjamin']),
                'umur_penjamin' => (int)$validated['umur_penjamin'],
                'pekerjaan_penjamin' => strip_tags($validated['pekerjaan_penjamin']),
                'hubungan_wbp' => strip_tags($validated['hubungan_wbp']),
                'alamat_penjamin' => strip_tags($validated['alamat_penjamin']),
                'no_hp_penjamin' => preg_replace('/[^0-9+]/', '', $validated['no_hp_penjamin']),
                'nama_wbp' => strip_tags($validated['nama_wbp']),
                'umur_wbp' => (int)$validated['umur_wbp'],
                'jenis_layanan' => strip_tags($validated['jenis_layanan']),
                'perkara' => strip_tags($validated['perkara']),
            ];

            // Map jenis layanan to database enum value
            $jenisProgram = $this->mapJenisProgram($requestData['jenis_layanan']);

            // PERBAIKAN: Simpan ke database HANYA SEKALI
            // Cek apakah sudah ada data yang sama dalam 5 menit terakhir untuk mencegah duplikasi
            $existingRecord = Integrasi::where('nik_penjamin', $requestData['nik_penjamin'])
                ->where('nama_wbp', $requestData['nama_wbp'])
                ->where('jenis_program', $jenisProgram)
                ->where('created_at', '>=', Carbon::now()->subMinutes(5))
                ->first();

            if (!$existingRecord) {
                // Simpan ke database agar bisa dikelola Admin
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
            }

            // Prepare data untuk PDF View
            $penjamin = (object)[
                'nama' => strtoupper($requestData['nama_penjamin']),
                'umur' => $requestData['umur_penjamin'],
                'pekerjaan' => $requestData['pekerjaan_penjamin'],
                'hubungan' => $requestData['hubungan_wbp'],
                'alamat' => $requestData['alamat_penjamin'],
                'telp' => $requestData['no_hp_penjamin'],
            ];

            $napi = (object)[
                'nama' => strtoupper($requestData['nama_wbp']),
                'umur' => $requestData['umur_wbp'],
            ];

            $program = $requestData['jenis_layanan'];
            $tanggal = Carbon::now()->locale('id')->translatedFormat('d F Y');

            // Data untuk view
            $data = [
                'penjamin' => $penjamin,
                'napi' => $napi,
                'program' => $program,
                'tanggal' => $tanggal,
            ];

            // Generate PDF dengan DOMPDF
            $pdf = Pdf::loadView('pdf.surat_jaminan', $data);
            
            // ATURAN 3: Set ukuran kertas F4 (Folio) secara manual dalam points
            // F4 = 21.59cm x 33.02cm = 609.448pt x 935.433pt
            $pdf->setPaper([0, 0, 609.448, 935.433], 'portrait');
            
            // Set options untuk DOMPDF
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'Times New Roman',
            ]);

            // Download PDF
            $namaFile = 'Surat_Jaminan_' . str_replace(' ', '_', $napi->nama) . '.pdf';
            return $pdf->download($namaFile);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation error - tampilkan ke user
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // SECURITY FIX: Log detail error, tapi tampilkan pesan generic ke user
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->except(['_token'])));
            
            return back()->with('error', 'Terjadi kesalahan saat membuat dokumen PDF. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.');
        }
    }

    /**
     * Preview Surat Jaminan di browser (untuk testing)
     */
    public function previewSuratJaminan(Request $request)
    {
        try {
            // Prepare data untuk PDF View (gunakan dummy data jika tidak ada request)
            $penjamin = (object)[
                'nama' => strtoupper($request->input('nama_penjamin', 'BUDI SANTOSO')),
                'umur' => $request->input('umur_penjamin', 45),
                'pekerjaan' => $request->input('pekerjaan_penjamin', 'Wiraswasta'),
                'hubungan' => $request->input('hubungan_wbp', 'Ayah Kandung'),
                'alamat' => $request->input('alamat_penjamin', 'Jl. Merdeka No. 123, Lamongan, Jawa Timur'),
                'telp' => $request->input('no_hp_penjamin', '081234567890'),
            ];

            $napi = (object)[
                'nama' => strtoupper($request->input('nama_wbp', 'AHMAD WIJAYA')),
                'umur' => $request->input('umur_wbp', 25),
            ];

            $program = $request->input('jenis_layanan', 'Pembebasan Bersyarat');
            $tanggal = Carbon::now()->locale('id')->translatedFormat('d F Y');

            // Data untuk view
            $data = [
                'penjamin' => $penjamin,
                'napi' => $napi,
                'program' => $program,
                'tanggal' => $tanggal,
            ];

            // Generate PDF dan tampilkan di browser
            $pdf = Pdf::loadView('pdf.surat_jaminan', $data);
            $pdf->setPaper([0, 0, 609.448, 935.433], 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'Times New Roman',
            ]);
            
            return $pdf->stream('Surat_Jaminan_Preview.pdf');
            
        } catch (\Exception $e) {
            \Log::error('PDF Preview Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat preview PDF.');
        }
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
}
