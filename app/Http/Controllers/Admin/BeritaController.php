<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        try {
            $berita = Berita::latest('tanggal')->get();
            return view('admin.berita.index', compact('berita'));
        } catch (\Exception $e) {
            Log::error('Error loading berita: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data berita.');
        }
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'tanggal' => 'required|date',
                'status' => 'required|in:draft,published',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            ]);

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                
                // Validasi tambahan MIME type di server level
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return back()->withErrors(['gambar' => 'File harus berupa gambar JPG atau PNG.'])->withInput();
                }
                
                // Generate nama file yang aman
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('berita', $filename, 'public');
                $validated['gambar'] = basename($path);
            }

            Berita::create($validated);

            return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating berita: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan berita. Silakan coba lagi.')->withInput();
        }
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'tanggal' => 'required|date',
                'status' => 'required|in:draft,published',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                
                // Validasi tambahan MIME type
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return back()->withErrors(['gambar' => 'File harus berupa gambar JPG atau PNG.'])->withInput();
                }
                
                // Hapus gambar lama dari berbagai kemungkinan lokasi (Modern & Legacy)
                $oldImage = $berita->gambar;
                if ($oldImage) {
                    // 1. Modern Storage (storage/app/public/berita/)
                    if (Storage::disk('public')->exists('berita/' . $oldImage)) {
                        Storage::disk('public')->delete('berita/' . $oldImage);
                    }
                    // 2. Legacy Uploads (public/uploads/)
                    if (file_exists(public_path('uploads/' . $oldImage))) {
                        @unlink(public_path('uploads/' . $oldImage));
                    }
                }
                
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('berita', $filename, 'public');
                $validated['gambar'] = basename($path);
            }

            // Update slug if judul changed
            if ($berita->judul !== $validated['judul']) {
                $validated['slug'] = Str::slug($validated['judul']) . '-' . $berita->id_berita;
            }

            $berita->update($validated);

            return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating berita: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui berita. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy(Berita $berita)
    {
        try {
            // Hapus fisik file gambar dari berbagai lokasi
            $oldImage = $berita->gambar;
            if ($oldImage) {
                // 1. Modern Storage
                if (Storage::disk('public')->exists('berita/' . $oldImage)) {
                    Storage::disk('public')->delete('berita/' . $oldImage);
                }
                // 2. Legacy Uploads
                if (file_exists(public_path('uploads/' . $oldImage))) {
                    @unlink(public_path('uploads/' . $oldImage));
                }
            }
            
            $berita->delete();

            return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting berita: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus berita. Silakan coba lagi.');
        }
    }
}
