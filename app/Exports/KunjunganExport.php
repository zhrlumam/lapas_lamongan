<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KunjunganExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Kunjungan::whereMonth('tanggal_kunjungan', $this->bulan)
                        ->whereYear('tanggal_kunjungan', $this->tahun)
                        ->select('tanggal_kunjungan', 'nama_pengunjung', 'nik', 'nama_wbp', 'status')
                        ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Kunjungan',
            'Nama Pengunjung',
            'NIK',
            'Nama WBP',
            'Status',
        ];
    }
}
