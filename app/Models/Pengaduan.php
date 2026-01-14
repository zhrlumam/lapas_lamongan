<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    
    protected $fillable = [
        'kode_tiket',
        'nama_pelapor',
        'kontak_pelapor',
        'judul_pengaduan',
        'isi_pengaduan',
        'foto_bukti',
        'status',
        // 'tanggapan' removed because there is a 'tanggapan' table.
        // If there is a 'tanggapan' column in pengaduan table? No, based on SQL dump tanggapan is a separate table.
        // But the previous code assumed it. I'll remove it to be safe and use the relationship.
        'kategori_id'
    ];

    public function balasan()
    {
        return $this->hasMany(BalasanPengaduan::class, 'pengaduan_id')->orderBy('created_at', 'asc');
    }

    public function getFotoBuktiUrlAttribute()
    {
        if (!$this->foto_bukti) return null;

        // 1. Cek di storage/bukti_pengaduan
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('bukti_pengaduan/' . $this->foto_bukti)) {
            return asset('storage/bukti_pengaduan/' . $this->foto_bukti);
        }

        // 2. Jika sudah absolute path atau di public/uploads
        if (Str::startsWith($this->foto_bukti, 'http')) return $this->foto_bukti;
        
        if (file_exists(public_path('uploads/' . $this->foto_bukti))) {
            return asset('uploads/' . $this->foto_bukti);
        }

        return null;
    }
}
