<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilLapas extends Model
{
    use HasFactory;

    protected $table = 'profil_lapas';
    protected $primaryKey = 'id_profil';
    public $timestamps = false;

    protected $fillable = [
        'nama_instansi',
        'deskripsi_singkat',
        'nama_kepala',
        'jabatan_kepala',
        'foto_kepala',
        'visi',
        'misi',
        'sejarah',
        'sambutan_kepala',
        'alamat'
    ];

    /**
     * Get profile or return default values
     * Prevents duplicate default object logic across controllers
     */
    public static function getOrDefault()
    {
        return static::first() ?? (object)[
            'nama_instansi' => 'Lembaga Pemasyarakatan Kelas IIB Lamongan',
            'deskripsi_singkat' => 'Lembaga Pemasyarakatan yang berkomitmen untuk memberikan pembinaan terbaik.',
            'nama_kepala' => '-',
            'foto_kepala' => null,
            'sambutan_kepala' => 'Selamat datang di website resmi Lembaga Pemasyarakatan Kelas IIB Lamongan.',
            'sejarah' => 'Informasi sejarah akan segera ditambahkan.',
            'visi' => 'Menjadi lembaga pemasyarakatan yang profesional dan terpercaya.',
            'misi' => 'Memberikan pembinaan yang berkualitas kepada warga binaan.',
            'alamat' => 'Jl. Sumargo No. 12, Tlogoanyar, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur 62218'
        ];
    }
}
