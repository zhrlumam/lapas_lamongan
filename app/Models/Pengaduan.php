<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    
    protected $fillable = [
        'kode_tiket',
        'nama_pelapor',
        'kontak_pelapor',
        'email_pelapor',
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
}
