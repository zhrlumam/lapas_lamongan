<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'tanggal',
        'kategori',
        'lokasi',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Accessor untuk URL gambar lengkap
    // Accessor untuk URL gambar lengkap
    public function getGambarUrlAttribute()
    {
        // 1. Cek di storage (Struktur Baru atau Relative Path)
        if ($this->gambar) {
            // Jika path sudah ada prefix folder (misal: 'galeri/abc.jpg')
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->gambar)) {
                return asset('storage/' . $this->gambar);
            }
            
            // Jika hanya filename, cek di folder 'galeri'
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('galeri/' . $this->gambar)) {
                return asset('storage/galeri/' . $this->gambar);
            }

            // Fallback: Cek di folder 'produk' jika tertukar saat seeding
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('produk/' . $this->gambar)) {
                return asset('storage/produk/' . $this->gambar);
            }
        }

        // 2. Cek di public/assets (Legacy manual)
        if ($this->gambar && file_exists(public_path('assets/' . $this->gambar))) {
            return asset('assets/' . $this->gambar);
        }

        // 3. Cek di public/uploads (Legacy upload lainnya)
        $filename = basename($this->gambar);
        if ($this->gambar && file_exists(public_path('uploads/' . $filename))) {
            return asset('uploads/' . $filename);
        }

        // Placeholder default
        return 'https://placehold.co/800x600/312e81/f59e0b?text=Galeri+Lapas';
    }

    // Scope untuk filter berdasarkan status
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
