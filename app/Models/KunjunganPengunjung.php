<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganPengunjung extends Model
{
    protected $table = 'kunjungan_pengunjung';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enabled to match migration

    protected $guarded = [];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id', 'id');
    }
}
