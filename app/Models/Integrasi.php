<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integrasi extends Model
{
    protected $table = 'integrasi';
    protected $guarded = [];

    // Casts for better display if needed
    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];
}
