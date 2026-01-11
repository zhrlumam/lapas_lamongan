<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilLapas extends Model
{
    use HasFactory;

    protected $table = 'profil_lapas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_instansi',
        'deskripsi_singkat',
        'sejarah',
        'alamat',
        'nama_kepala',
        'jabatan_kepala',
        'foto_kepala',
        'sambutan_kepala',
        'visi',
        'misi',
        'latitude',
        'longitude',
        'embed_map',
    ];
}
