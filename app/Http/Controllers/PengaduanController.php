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
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'telepon'      => 'required|numeric', // Input name is telepon
            'isi_pengaduan'=> 'required|string',
            'bukti_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Input name is bukti_file
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path('bukti_pengaduan');
            if(!file_exists($path)){
                mkdir($path, 0777, true);
            }
            $file->move($path, $fileName);
            // Save ONLY filename as per DB example 'ADUAN_....jpg' or relative path?
            // Existing data: 'ADUAN_1767409235.jpg'. It seems it stores filename only.
            // But let's verify where it is stored. For now I store full relative path for Laravel convention,
            // or just filename if the original app expects it.
            // Let's store 'bukti_pengaduan/filename' to be safe with my storage logic.
            $filePath = 'bukti_pengaduan/' . $fileName;
        }

        // Generate Ticket Code
        $kode_tiket = 'ADU-' . strtoupper(Str::random(5));
        while(Pengaduan::where('kode_tiket', $kode_tiket)->exists()) {
            $kode_tiket = 'ADU-' . strtoupper(Str::random(5));
        }

        // Create with DB column mapping
        $pengaduan = Pengaduan::create([
            'kode_tiket'      => $kode_tiket,
            'nama_pelapor'    => $request->nama_pelapor,
            'kontak_pelapor'  => $request->telepon, 
            'email_pelapor'   => null,
            'judul_pengaduan' => 'Laporan Masyarakat via Website',
            'isi_pengaduan'   => $request->isi_pengaduan,
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
