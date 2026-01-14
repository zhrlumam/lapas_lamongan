<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enabled to match migration

    // SECURITY FIX: Explicit fillable untuk mencegah mass assignment vulnerability
    protected $fillable = [
        'nama_wbp',
        'tanggal_kunjungan',
        'nomor_antrian',
        'status',
        'waktu_kunjungan',
        'nama_pengunjung',
        'nik',
        'no_telp',
        'barang_bawaan',
        'alamat',
        'jk',
        'hubungan',
        'check_in_at',
        'check_out_at',
    ];

    public function pengunjung()
    {
        return $this->hasMany(KunjunganPengunjung::class, 'kunjungan_id', 'id');
    }
}
