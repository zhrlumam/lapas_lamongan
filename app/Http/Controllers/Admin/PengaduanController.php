<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\BalasanPengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        $data = Pengaduan::latest()->paginate(10);
        
        // Menghitung statistik untuk Dashboard
        $stats = Pengaduan::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Masuk' THEN 1 ELSE 0 END) as masuk,
            SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
            SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai
        ")->first();

        return view('admin.pengaduan.index', compact('data', 'stats'));
    }

    public function show($id)
    {
        $item = Pengaduan::with('balasan')->findOrFail($id);
        return view('admin.pengaduan.show', compact('item'));
    }

    public function updateStatus(Request $request, $id)
    {
        // SECURITY FIX: Validasi enum status sesuai database
        $request->validate([
            'status' => 'required|in:Masuk,Diproses,Selesai,Ditolak'
        ]);
        
        $item = Pengaduan::findOrFail($id);
        $item->update(['status' => $request->status]);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['isi_balasan' => 'required']);
        
        $item = Pengaduan::findOrFail($id);
        
        BalasanPengaduan::create([
            'pengaduan_id' => $id,
            'pengirim'     => 'admin',
            'isi_balasan'  => $request->isi_balasan,
            'is_read'      => false
        ]);

        // Auto change status to 'Diproses' if currently 'Masuk'
        if ($item->status == 'Masuk') {
            $item->update(['status' => 'Diproses']);
        }


        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function destroy($id)
    {
        $item = Pengaduan::findOrFail($id);
        
        // Delete related replies if any
        $item->balasan()->delete();
        
        $item->delete();

        return back()->with('success', 'Pengaduan berhasil dihapus.');
    }
}
