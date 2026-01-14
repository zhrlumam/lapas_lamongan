<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\BalasanPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function store(Request $request)
    {
        // SECURITY FIX: Tambahkan sanitasi untuk mencegah XSS
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'telepon'      => 'required|numeric',
            'isi_pengaduan'=> 'required|string|max:5000', // Batasi panjang
            'bukti_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        
        // Sanitasi input untuk mencegah XSS
        $namaPelapor = strip_tags($request->nama_pelapor);
        $isiPengaduan = strip_tags($request->isi_pengaduan);

        // SECURITY FIX: Enhanced file upload security
        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            
            // Validasi MIME type di server-side
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 
                           'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return back()->withErrors(['bukti_file' => 'File harus berupa gambar (JPG/PNG), PDF, atau dokumen Word.'])->withInput();
            }
            
            // Generate random filename untuk keamanan
            $extension = $file->getClientOriginalExtension();
            $fileName = 'ADUAN_' . time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $extension;
            
            // Simpan di storage/app/public/bukti_pengaduan (lebih aman)
            $path = $file->storeAs('bukti_pengaduan', $fileName, 'public');
            $filePath = $fileName; // Simpan nama file saja
        }

        // Generate Ticket Code
        $kode_tiket = 'ADU-' . strtoupper(Str::random(5));
        while(Pengaduan::where('kode_tiket', $kode_tiket)->exists()) {
            $kode_tiket = 'ADU-' . strtoupper(Str::random(5));
        }

        // Create with DB column mapping (gunakan data yang sudah disanitasi)
        $pengaduan = Pengaduan::create([
            'kode_tiket'      => $kode_tiket,
            'nama_pelapor'    => $namaPelapor,
            'kontak_pelapor'  => $request->telepon, 
            'judul_pengaduan' => 'Laporan Masyarakat via Website',
            'isi_pengaduan'   => $isiPengaduan,
            'foto_bukti'      => $filePath, 
            'status'          => 'Masuk', // FIX: Sesuai Enum Database ('Masuk')
            'kategori_id'     => 8,
        ]);

        return redirect()->route('pengaduan.sukses', ['kode' => $kode_tiket]);
    }

    public function sukses($kode)
    {
        $pengaduan = Pengaduan::where('kode_tiket', $kode)->firstOrFail();
        return view('frontend.pengaduan_sukses', compact('pengaduan'));
    }

    public function cari(Request $request)
    {
        $request->validate([
            'telepon' => 'required|numeric'
        ]);

        $telepon = trim($request->telepon);
        // Search using 'kontak_pelapor'
        $pengaduanList = Pengaduan::where('kontak_pelapor', $telepon)->orderBy('created_at', 'desc')->get();

        if ($pengaduanList->isEmpty()) {
            return back()->with('error', 'Nomor Telepon tidak ditemukan. Silakan cek kembali atau buat laporan baru.');
        }

        // Logic "Auto Redirect" dihapus agar lebih stabil menangani data lama yang mungkin belum punya kode_tiket.
        // User akan selalu diarahkan ke halaman riwayat/daftar laporan.
        
        return view('frontend.pengaduan_riwayat', compact('pengaduanList', 'telepon'));
    }

    public function detail($kode)
    {
        $pengaduan = Pengaduan::where('kode_tiket', $kode)->with('balasan')->firstOrFail();
        return view('frontend.pengaduan_detail', compact('pengaduan'));
    }

    public function reply(Request $request, $kode)
    {
        $request->validate([
            'isi_balasan' => 'required|string'
        ]);

        $pengaduan = Pengaduan::where('kode_tiket', $kode)->firstOrFail();

        BalasanPengaduan::create([
            'pengaduan_id' => $pengaduan->id,
            'pengirim'     => 'pelapor',
            'isi_balasan'  => $request->isi_balasan,
            'is_read'      => false
        ]);

        return back()->with('success', 'Pesan Anda terkirim.');
    }
}
