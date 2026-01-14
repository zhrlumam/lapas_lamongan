<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $galeri = Galeri::orderBy('tanggal', 'desc')->paginate(10);
            return view('admin.galeri.index', compact('galeri'));
        } catch (\Exception $e) {
            Log::error('Error loading galeri: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data galeri.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'tanggal' => 'required|date',
                'kategori' => 'required|string',
                'lokasi' => 'nullable|string|max:255',
                'status' => 'required|in:draft,published'
            ]);

            // Upload gambar dengan validasi tambahan
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                
                // Validasi MIME type di server level
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return back()->withErrors(['gambar' => 'File harus berupa gambar (JPG, PNG, atau GIF).'])->withInput();
                }
                
                // Validasi ukuran file (max 2MB)
                if ($file->getSize() > 2048000) {
                    return back()->withErrors(['gambar' => 'Ukuran file maksimal 2MB.'])->withInput();
                }
                
                // Generate nama file yang aman
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('galeri', $filename, 'public');
                $validated['gambar'] = $path;
            }

            Galeri::create($validated);

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating galeri: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan galeri. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Galeri $galeri)
    {
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galeri $galeri)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'tanggal' => 'required|date',
                'kategori' => 'required|string',
                'lokasi' => 'nullable|string|max:255',
                'status' => 'required|in:draft,published'
            ]);

            // Upload gambar baru jika ada
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                
                // Validasi MIME type
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return back()->withErrors(['gambar' => 'File harus berupa gambar (JPG, PNG, atau GIF).'])->withInput();
                }
                
                // Validasi ukuran
                if ($file->getSize() > 2048000) {
                    return back()->withErrors(['gambar' => 'Ukuran file maksimal 2MB.'])->withInput();
                }
                
                // Hapus gambar lama dari berbagai kemungkinan lokasi (Modern & Legacy)
                $oldPath = $galeri->gambar;
                if ($oldPath) {
                    // 1. Path yang tersimpan di DB (Modern/Relative)
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                    // 2. Cek di legacy uploads (public/uploads/)
                    $filename = basename($oldPath);
                    if (file_exists(public_path('uploads/' . $filename))) {
                        @unlink(public_path('uploads/' . $filename));
                    }
                }
                
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('galeri', $filename, 'public');
                $validated['gambar'] = $path;
            }

            $galeri->update($validated);

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating galeri: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui galeri. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galeri $galeri)
    {
        try {
            // Hapus fisik file gambar dari berbagai lokasi
            $oldPath = $galeri->gambar;
            if ($oldPath) {
                // 1. Path di storage
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                // 2. Legacy uploads
                $filename = basename($oldPath);
                if (file_exists(public_path('uploads/' . $filename))) {
                    @unlink(public_path('uploads/' . $filename));
                }
            }

            $galeri->delete();

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting galeri: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus galeri. Silakan coba lagi.');
        }
    }
}
