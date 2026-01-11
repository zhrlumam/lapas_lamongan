<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengaduan extends Model
{
    protected $table = 'kategori_pengaduan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];
}
