<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WargaBinaan;
use Illuminate\Http\Request;

class HunianController extends Controller
{
    public function index()
    {
        $hunian = WargaBinaan::latest('tanggal_update')->get();
        $latest = WargaBinaan::latest('tanggal_update')->first();
        return view('admin.hunian.index', compact('hunian', 'latest'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['total_penghuni'] = $request->tahanan + $request->narapidana;
        $data['tanggal_update'] = date('Y-m-d');
        
        WargaBinaan::create($data);
        return redirect()->back()->with('success', 'Data hunian berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $hunian = WargaBinaan::findOrFail($id);
            
            // SECURITY: Cegah hapus data terbaru (untuk keamanan data)
            $latest = WargaBinaan::latest('tanggal_update')->first();
            if ($latest && $latest->id_data == $id) {
                return back()->with('error', 'Data terbaru tidak dapat dihapus. Silakan hapus data lain terlebih dahulu.');
            }
            
            $hunian->delete();
            return back()->with('success', 'Data hunian berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data hunian.');
        }
    }
}
