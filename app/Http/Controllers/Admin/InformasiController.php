<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InformasiController extends Controller
{
    public function index()
    {
        $informasi = Informasi::latest('id_info')->get();
        return view('admin.informasi.index', compact('informasi'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul_info'        => 'required|string|max:255',
                'deskripsi_singkat' => 'required|string',
                'link_tujuan'       => 'nullable|string',
            ]);

            // Auto-fill tanggal_info karena tidak ada di form
            $validated['tanggal_info'] = date('Y-m-d');

            Informasi::create($validated);
            
            return redirect()->back()->with('success', 'Informasi berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error Info Store: " . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan informasi.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Informasi::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Informasi berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus informasi.');
        }
    }
}
