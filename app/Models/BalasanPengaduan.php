<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalasanPengaduan extends Model
{
    protected $table = 'balasan_pengaduan';
    
    protected $fillable = [
        'pengaduan_id',
        'pengirim', // 'admin', 'pelapor'
        'isi_balasan',
        'is_read'
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id');
    }
}
