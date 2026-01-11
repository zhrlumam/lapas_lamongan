<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\KunjunganPengunjung;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KunjunganController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // WBP Info
            'nama_wbp' => 'required|string|max:255',
            'status_wbp' => 'required|string',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'sesi' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'barang_bawaan' => 'nullable|string|max:500',
            
            // Pengunjung Arrays (Max 5)
            'pengunjung' => 'required|array|min:1|max:5',
            'pengunjung.*.nama' => 'required|string|max:255',
            'pengunjung.*.nik' => 'required|string|size:16',
            'pengunjung.*.jk' => 'required|in:Laki-Laki,Perempuan',
            'pengunjung.*.hubungan' => 'required|string',
            'pengunjung.*.alamat' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $tanggal = $request->tanggal_kunjungan;
            
            // Perbaikan: Ambil nomor_antrian terakhir (INT)
            $lastEntry = Kunjungan::where('tanggal_kunjungan', $tanggal)
                ->orderBy('id', 'desc')
                ->first();
                
            $nextNumber = 1;
            if ($lastEntry && $lastEntry->nomor_antrian) {
                // Karena kolom nomor_antrian INT, langsung tambah 1
                $nextNumber = $lastEntry->nomor_antrian + 1;
            }
            
            // Format untuk Tampilan Tiket (A-01, A-02, dst)
            // Note: $nomorAntreanFormatted ini dipake buat preview doang kalo mau, tapi di DB simpannya INT
             $nomorAntreanFormatted = "A-" . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            // 1. Simpan ke tabel 'kunjungan'
            // FIX: Mengembalikan ke nama kolom yang BENAR-BENAR ada di database user saat ini.
            // Error sebelumnya menunjukkan 'no_antrean' tidak ada, berarti pakenya 'nomor_antrian'.
            // Error sebelumnya menunjukkan 'status_wbp' tidak ada.
            // Juga kolom wajib lain seperti 'waktu_kunjungan', 'nama_pengunjung', 'nik' harus diisi.
            
            $kunjungan = Kunjungan::create([
                'nama_wbp' => $request->nama_wbp . ' (' . $request->status_wbp . ' - ' . $request->sesi . ')', 
                'tanggal_kunjungan' => $tanggal,
                'nomor_antrian' => $nextNumber, // Simpan ANGKA (INT) saja
                'status' => 'approved', // Enum: pending/approved/rejected
                'waktu_kunjungan' => '08:00:00', // Default
                'nama_pengunjung' => $request->pengunjung[0]['nama'], // Ambil dari input array
                'nik' => $request->pengunjung[0]['nik'], // Ambil dari input array
                'no_telp' => $request->no_telp,
                'barang_bawaan' => $request->barang_bawaan,
                'alamat' => $request->pengunjung[0]['alamat'],
                'jk' => $request->pengunjung[0]['jk'],
                'hubungan' => $request->pengunjung[0]['hubungan'],
            ]);

            // 2. Simpan detail ke 'kunjungan_pengunjung'
            foreach ($request->pengunjung as $p) {
                KunjunganPengunjung::create([
                    'kunjungan_id' => $kunjungan->id,
                    'nama_pengunjung' => $p['nama'],
                    'nik_pengunjung' => $p['nik'],
                    'jk' => $p['jk'],
                    'hubungan' => $p['hubungan'],
                    'alamat' => $p['alamat'] ?? '-',
                ]);
            }

            DB::commit();
            return redirect()->route('kunjungan.tiket', ['id' => $kunjungan->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function tiket(Request $request, $id)
    {
        // Muat data kunjungan beserta relasi pengunjungnya
        $kunjungan = Kunjungan::with('pengunjung')->findOrFail($id);
        return view('frontend.kunjungan_tiket', compact('kunjungan'));
    }

    public function cari(Request $request)
    {
        $keyword = $request->query('keyword');
        if (!$keyword) {
            return redirect()->back()->withErrors(['keyword' => 'Masukkan NIK atau Nama Pengunjung!']);
        }

        // Pencarian Berdasarkan NIK atau Nama (sesuai request user: "PAKAI NIK/ NAMA AJA")
        // Kita cari di tabel utama (kunjungan) dan tabel detail (kunjungan_pengunjung)
        
        $kunjungan = Kunjungan::where(function($query) use ($keyword) {
                $query->where('nik', $keyword)
                      ->orWhere('nama_pengunjung', 'LIKE', '%' . $keyword . '%');
            })
            ->orWhereHas('pengunjung', function($q) use ($keyword) {
                 $q->where('nik_pengunjung', $keyword)
                   ->orWhere('nama_pengunjung', 'LIKE', '%' . $keyword . '%');
            })
            ->orderBy('tanggal_kunjungan', 'desc') 
            ->orderBy('id', 'desc')
            ->first();

        if ($kunjungan) {
            return redirect()->route('kunjungan.tiket', ['id' => $kunjungan->id]);
        } else {
            return redirect()->back()->withErrors(['keyword' => 'Tiket tidak ditemukan untuk NIK/Nama: ' . $keyword]);
        }
    }
}
