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
        return view('admin.pengaduan.index', compact('data'));
    }

    public function show($id)
    {
        $item = Pengaduan::with('balasan')->findOrFail($id);
        return view('admin.pengaduan.show', compact('item'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required']);
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
