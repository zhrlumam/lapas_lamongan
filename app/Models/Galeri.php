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
        // 1. Cek di storage (upload via Admin Panel baru: storage/app/public/galeri)
        // Controller menyimpan dengan path relative: "galeri/filename.jpg"
        if ($this->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->gambar)) {
            return asset('storage/' . $this->gambar);
        }

        // 2. Cek di public/assets (legacy manual)
        if ($this->gambar && file_exists(public_path('assets/' . $this->gambar))) {
            return asset('assets/' . $this->gambar);
        }

        // 3. Cek di public/uploads (legacy upload lainnya)
        if ($this->gambar && file_exists(public_path('uploads/' . basename($this->gambar)))) {
            return asset('uploads/' . basename($this->gambar));
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
