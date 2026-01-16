<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'kategori',
        'deskripsi',
        'gambar',
    ];

    protected $appends = ['gambar_url'];

    public function getGambarUrlAttribute()
    {
        // 1. Cek di storage/produk (Struktur Baru)
        if ($this->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists('produk/' . $this->gambar)) {
            return asset('storage/produk/' . $this->gambar);
        }

        // 2. Cek di public/uploads (Legacy)
        if ($this->gambar && file_exists(public_path('uploads/' . $this->gambar))) {
            return asset('uploads/' . $this->gambar);
        }
        
        return 'https://placehold.co/800x600/312e81/f59e0b?text=Produk+Unggulan';
    }
}
