<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyKepuasan extends Model
{
    use HasFactory;

    protected $table = 'survey_kepuasan';
    protected $primaryKey = 'id_survey';
    public $timestamps = false;

    protected $fillable = [
        'bulan',
        'skor_ipk',
        'skor_ikm',
        'jumlah_responden',
        'keterangan',
        'is_active',
    ];
}
