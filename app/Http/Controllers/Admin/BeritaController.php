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
                $path = $file->storeAs('uploads', $filename, 'public');
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

    public function edit($id)
    {
        try {
            $berita = Berita::findOrFail($id);
            return view('admin.berita.edit', compact('berita'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.berita.index')->with('error', 'Berita tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $berita = Berita::findOrFail($id);
            
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'tanggal' => 'required|date',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                
                // Validasi tambahan MIME type
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return back()->withErrors(['gambar' => 'File harus berupa gambar JPG atau PNG.'])->withInput();
                }
                
                // Delete old image
                if ($berita->gambar && Storage::disk('public')->exists('uploads/' . $berita->gambar)) {
                    Storage::disk('public')->delete('uploads/' . $berita->gambar);
                }
                
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads', $filename, 'public');
                $validated['gambar'] = basename($path);
            }

            $berita->update($validated);

            return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.berita.index')->with('error', 'Berita tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating berita: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui berita. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $berita = Berita::findOrFail($id);
            
            // Delete image file
            if ($berita->gambar && Storage::disk('public')->exists('uploads/' . $berita->gambar)) {
                Storage::disk('public')->delete('uploads/' . $berita->gambar);
            }
            
            $berita->delete();

            return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.berita.index')->with('error', 'Berita tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error deleting berita: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus berita. Silakan coba lagi.');
        }
    }
}
