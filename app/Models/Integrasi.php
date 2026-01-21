<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integrasi extends Model
{
    protected $table = 'integrasi';
    // SECURITY FIX: Explicit fillable untuk mencegah mass assignment vulnerability
    protected $fillable = [
        'nama_penjamin',
        'nik_penjamin',
        'umur_penjamin',
        'pekerjaan_penjamin',
        'hubungan_penjamin',
        'alamat_penjamin',
        'telepon_penjamin',
        'nama_wbp',
        'umur_wbp',
        'perkara',
        'alasan_penolakan',
        'jenis_program',
        'tanggal_pengajuan',
        'status',
        'file_surat',
    ];

    // Casts for better display if needed
    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];
}
