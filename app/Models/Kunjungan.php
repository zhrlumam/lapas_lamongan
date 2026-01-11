<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'id';
    public $timestamps = false; // native uses created_at timestamp default current_timestamp()

    protected $guarded = [];

    public function pengunjung()
    {
        return $this->hasMany(KunjunganPengunjung::class, 'kunjungan_id', 'id');
    }
}
