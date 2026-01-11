<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi';
    protected $primaryKey = 'id_info';
    public $timestamps = false;

    protected $fillable = [
        'judul_info',
        'deskripsi_singkat',
        'link_tujuan',
        'tanggal_info',
    ];
}
