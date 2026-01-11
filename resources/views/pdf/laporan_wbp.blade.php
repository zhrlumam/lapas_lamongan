<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian WBP</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        .footer { margin-top: 30px; text-align: right; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>KEMENTERIAN HUKUM DAN HAM RI</h2>
        <h3>KANTOR WILAYAH JAWA TIMUR</h3>
        <h2>{{ $profil->nama_instansi ?? 'LAPAS KELAS IIB LAMONGAN' }}</h2>
        <p>{{ $profil->alamat ?? '' }} | Telp: {{ $profil->telepon ?? '' }}</p>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">LAPORAN HARIAN JUMLAH PENGHUNI</h3>
    <p>Tanggal Cetak: {{ date('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Update</th>
                <th>Tahanan</th>
                <th>Narapidana</th>
                <th>Sidang</th>
                <th>Berobat Luar</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_update)->format('d-m-Y') }}</td>
                <td>{{ $item->tahanan }}</td>
                <td>{{ $item->narapidana }}</td>
                <td>{{ $item->sidang }}</td>
                <td>{{ $item->berobat_luar }}</td>
                <td><strong>{{ $item->total_penghuni }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Lamongan, {{ date('d F Y') }}</p>
        <div class="signature">
            <p>Kepala {{ $profil->nama_instansi ?? 'Lapas Kelas IIB Lamongan' }}</p>
            <br><br><br>
            <p><strong>{{ $profil->nama_kepala ?? '..........................' }}</strong></p>
            <p>NIP. ..........................</p>
        </div>
    </div>
</body>
</html>
