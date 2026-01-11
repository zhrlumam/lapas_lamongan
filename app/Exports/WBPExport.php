<?php

namespace App\Exports;

use App\Models\WargaBinaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WBPExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return WargaBinaan::select('tanggal_update', 'tahanan', 'narapidana', 'sidang', 'berobat_luar', 'total_penghuni')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Update',
            'Tahanan',
            'Narapidana',
            'Sidang',
            'Berobat Luar',
            'Total Penghuni',
        ];
    }
}
