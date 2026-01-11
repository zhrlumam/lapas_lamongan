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
        'isi',
        'tanggal',
        'gambar',
    ];

    public function getGambarUrlAttribute()
    {
        // Cek di storage (upload via Admin Panel baru)
        if ($this->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists('uploads/' . $this->gambar)) {
            return asset('storage/uploads/' . $this->gambar);
        }

        // Cek di public/uploads (legacy / manual upload)
        if ($this->gambar && file_exists(public_path('uploads/' . $this->gambar))) {
            return asset('uploads/' . $this->gambar);
        }
        
        // Placeholder jika tidak ada gambar
        return 'https://placehold.co/800x600/312e81/f59e0b?text=Lapas+Lamongan';
    }
}
