<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WargaBinaan extends Model
{
    use HasFactory;

    protected $table = 'data_warga_binaan';
    protected $primaryKey = 'id_data';
    public $timestamps = false;

    protected $fillable = [
        'tahanan',
        'narapidana',
        'sidang',
        'berobat_luar',
        'total_penghuni',
        'tanggal_update',
    ];
}
