<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::latest('id_produk')->get();
        return view('admin.produk.index', compact('produk'));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validasi Input
            $validated = $request->validate([
                'nama_produk' => 'required|string|max:255',
                'kategori'    => 'required|string',
                'deskripsi'   => 'required|string',
                'gambar'      => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            ]);

            // 2. Handling Upload Gambar (Ke public/uploads untuk kompatibilitas View)
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                // Sanitasi nama file agar aman
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('produk', $filename, 'public');
                $validated['gambar'] = basename($path);
            }

            // 3. Simpan Database
            Produk::create($validated);

            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error Validasi
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Error Sistem Lainnya
            Log::error("Error Store Produk: " . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data. Silakan coba lagi.')->withInput();
        }
    }

    public function edit($id)
    {
        // CRUD Read Single for Edit
        $produkElement = Produk::findOrFail($id);
        $produk = Produk::latest('id_produk')->get(); // Tetap load list untuk sidebar
        return view('admin.produk.edit', compact('produkElement', 'produk'));
    }

    public function update(Request $request, $id)
    {
        try {
            $item = Produk::findOrFail($id);

            // 1. Validasi Input (Gambar nullable/optional saat update)
            $validated = $request->validate([
                'nama_produk' => 'required|string|max:255',
                'kategori'    => 'required|string',
                'deskripsi'   => 'required|string',
                'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // 2. Handling Upload Jika Ada Gambar Baru
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

                // Hapus gambar lama dari berbagai kemungkinan lokasi (Modern & Legacy)
                $oldImage = $item->gambar;
                if ($oldImage) {
                    // 1. Cek di storage/app/public/produk/
                    if (Storage::disk('public')->exists('produk/' . $oldImage)) {
                        Storage::disk('public')->delete('produk/' . $oldImage);
                    }
                    // 2. Cek di public/uploads/
                    if (file_exists(public_path('uploads/' . $oldImage))) {
                        @unlink(public_path('uploads/' . $oldImage));
                    }
                }
                
                $path = $file->storeAs('produk', $filename, 'public');
                $validated['gambar'] = basename($path);
            } else {
                // Jangan update kolom gambar jika tidak ada file baru
                unset($validated['gambar']);
            }

            // 3. Update Database
            $item->update($validated);

            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error Update Produk: " . $e->getMessage());
            return back()->with('error', 'Gagal update data.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = Produk::findOrFail($id);
            
            // Hapus fisik file gambar dari berbagai lokasi
            $oldImage = $item->gambar;
            if ($oldImage) {
                // 1. Modern Storage
                if (Storage::disk('public')->exists('produk/' . $oldImage)) {
                    Storage::disk('public')->delete('produk/' . $oldImage);
                }
                // 2. Legacy Uploads
                if (file_exists(public_path('uploads/' . $oldImage))) {
                    @unlink(public_path('uploads/' . $oldImage));
                }
            }

            $item->delete();
            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}
