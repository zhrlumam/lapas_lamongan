<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $primaryKey = 'id_berita';
    public $timestamps = false; // Based on SQL dump, it uses datetime current_timestamp() for 'tanggal'

    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'tanggal',
        'gambar',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = \Illuminate\Support\Str::slug($berita->judul) . '-' . uniqid();
            }
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('judul') && empty($berita->slug)) {
                $berita->slug = \Illuminate\Support\Str::slug($berita->judul) . '-' . uniqid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getGambarUrlAttribute()
    {
        // 1. Cek di storage/berita (Struktur Baru)
        if ($this->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists('berita/' . $this->gambar)) {
            return asset('storage/berita/' . $this->gambar);
        }

        // 2. Cek di storage/uploads (Legacy Storage)
        if ($this->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists('uploads/' . $this->gambar)) {
            return asset('storage/uploads/' . $this->gambar);
        }

        // 3. Cek di public/uploads (Legacy Public)
        if ($this->gambar && file_exists(public_path('uploads/' . $this->gambar))) {
            return asset('uploads/' . $this->gambar);
        }
        
        // Placeholder jika tidak ada gambar
        return 'https://placehold.co/800x600/312e81/f59e0b?text=Lapas+Lamongan';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
