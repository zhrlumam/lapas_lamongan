<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekapitulasi Kunjungan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .footer { margin-top: 30px; float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAPITULASI KUNJUNGAN WARGA BINAAN</h2>
        <h3>{{ $profil->nama_instansi ?? 'LAPAS KELAS IIB LAMONGAN' }}</h3>
        <p>Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Tanggal</th>
                <th>Nama Pengunjung</th>
                <th width="100">NIK</th>
                <th>Hubungan</th>
                <th>Nama WBP</th>
                <th width="70">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d-m-Y') }}</td>
                <td>{{ $item->nama_pengunjung }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->hubungan ?? '-' }}</td>
                <td>{{ $item->nama_wbp }}</td>
                <td style="text-align: center; text-transform: uppercase;">{{ $item->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data kunjungan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Lamongan, {{ date('d F Y') }}</p>
        <p>Petugas Registrasi,</p>
        <br><br><br>
        <p><strong>........................................</strong></p>
        <p>NIP. ................................</p>
    </div>
</body>
</html>
