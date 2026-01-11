<!DOCTYPE html>
<html>
<head>
    <title>Surat Jaminan Integrasi</title>
    <style>
        @page { margin: 0.8cm 2cm; }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 10.5pt; 
            color: #000;
            line-height: 1.15;
        }
        
        /* Helper Utilities */
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        
        /* Layout Tables */
        table { width: 100%; border-collapse: collapse; border: 0; }
        td { vertical-align: top; padding: 1px 0; }
        
        /* Header Specifics */
        .header-table { border-bottom: 3px solid #000; margin-bottom: 15px; }
        .header-table td { padding-bottom: 5px; vertical-align: middle; }
        .logo-cell { width: 100px; text-align: center; }
        .text-cell { text-align: center; padding-left: 5px; }
        
        .kop-1 { font-size: 11pt; margin: 0; }
        .kop-2 { font-size: 11pt; margin: 0; }
        .kop-3 { font-size: 11pt; margin: 0; }
        .kop-main { font-size: 13pt; font-weight: bold; margin: 2px 0; }
        .kop-addr { font-size: 9pt; margin: 0; }
        
        /* Content Specifics */
        .doc-title { font-size: 12pt; margin-bottom: 12px; }
        
        .data-table td.label { width: 190px; }
        .data-table td.sep { width: 15px; text-align: center; }
        
        .stmt-list { margin-top: 5px; padding-left: 25px; }
        .stmt-list li { margin-bottom: 4px; line-height: 1.25; text-align: justify; }
        
        /* Footer / Signatures */
        .footer-table { margin-top: 15px; }
        .footer-table td { text-align: center; padding-top: 5px; }
        .sign-buffer { height: 60px; }
        
        /* Materai Box - NO FLEXBOX */
        .materai-placeholder { 
            width: 70px; 
            height: 45px; 
            border: 1px solid #000; 
            margin: 5px auto; 
            font-size: 8pt; 
            line-height: 45px; /* Vertically center single line text, or approximated */
            text-align: center;
        }
        
        .note { font-size: 8pt; margin-top: 10px; }
    </style>
</head>
<body>
    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <!-- LOGO VECTOR (SVG) - ANTIGRAVITY LINK -->
                <!-- Menggunakan SVG agar tidak butuh GD Extension / Server Restart -->
                <svg width="90" height="90" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle (Blue) -->
                    <circle cx="50" cy="50" r="48" fill="#005099" stroke="#DAA520" stroke-width="2"/>
                    
                    <!-- Text Arc (Simplified representation) -->
                    <path id="curve" d="M 15 50 A 35 35 0 0 1 85 50" fill="none"/>
                    
                    <!-- Center Emblem (PENGAYOMAN - Tree Shape) -->
                    <path d="M50 20 Q70 20 70 45 Q70 70 50 80 Q30 70 30 45 Q30 20 50 20 Z" fill="#DAA520"/>
                    <text x="50" y="50" font-family="Arial" font-size="6" fill="#000" text-anchor="middle" font-weight="bold" dy="0.3em">PENGAYOMAN</text>
                    
                    <!-- Stars / Decoration -->
                    <circle cx="50" cy="30" r="3" fill="#FFFFFF"/>
                </svg>
            </td>
            <td class="text-cell">
                <div class="kop-1 uppercase">KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA</div>
                <div class="kop-2 uppercase">DIREKTORAT JENDERAL PEMASYARAKATAN</div>
                <div class="kop-3 uppercase">KANTOR WILAYAH JAWA TIMUR</div>
                <div class="kop-main uppercase">LEMBAGA PEMASYARAKATAN KELAS IIB LAMONGAN</div>
                <div class="kop-addr">Jalan Sumargo Nomor 19 Lamongan</div>
                <div class="kop-addr">
                    Laman : <span style="color: black;">lapaslamongan.kemenkumham.go.id</span> &nbsp; 
                    Pos-el. <span style="color: blue; text-decoration: underline;">lapaslamongan@ymail.com</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="text-center doc-title uppercase text-bold underline">
        SURAT JAMINAN KESANGGUPAN KELUARGA
    </div>

    <!-- DATA PENJAMIN -->
    <div style="margin-bottom: 5px;">Yang bertanda tangan di bawah ini :</div>
    <table class="data-table">
        <tr>
            <td class="label">Nama</td>
            <td class="sep">:</td>
            <td class="text-bold">{{ $data['nama_penjamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Umur</td>
            <td class="sep">:</td>
            <td>{{ $data['umur_penjamin'] ?? '-' }} Tahun</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="sep">:</td>
            <td>{{ $data['pekerjaan_penjamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Hubungan dengan narapidana</td>
            <td class="sep">:</td>
            <td>{{ $data['hubungan_wbp'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="sep">:</td>
            <td>{{ $data['alamat_penjamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Telp / Handphone</td>
            <td class="sep">:</td>
            <td>{{ $data['no_hp_penjamin'] ?? '-' }}</td>
        </tr>
    </table>

    <!-- DATA WBP -->
    <div style="margin-top: 10px; margin-bottom: 5px;">Adalah sebagai Penjamin dari Narapidana yaitu :</div>
    <table class="data-table">
        <tr>
            <td class="label">Nama</td>
            <td class="sep">:</td>
            <td class="text-bold">{{ $data['nama_wbp'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Umur</td>
            <td class="sep">:</td>
            <td>{{ $data['umur_wbp'] ?? '-' }} Tahun</td>
        </tr>
        <tr>
            <td class="label">Menjalani Pidana di</td>
            <td class="sep">:</td>
            <td>Lembaga Pemasyarakatan Kelas IIB Lamongan</td>
        </tr>
    </table>

    <!-- PERNYATAAN -->
    <div style="margin-top: 10px;">Dengan ini menyatakan :</div>
    @php
        $list_layanan = [
            'Program Pembebasan Bersyarat',
            'Cuti Menjelang Bebas',
            'Asimilasi Kerja Sosial',
            'Asimilasi Pihak Ketiga',
            'Cuti Bersyarat'
        ];
        
        $u_layanan = $data['jenis_layanan'] ?? '';
        
        $formatted_list = array_map(function($item) use ($u_layanan) {
            $clean_item = str_replace('Program ', '', $item);
            if (strcasecmp($clean_item, $u_layanan) == 0) {
                return '<b>' . $item . '</b>';
            }
            return $item;
        }, $list_layanan);
        
        $layanan_string = implode(', ', $formatted_list);
    @endphp

    <ol class="stmt-list">
        <li>Sanggup menjamin sepenuhnya bahwa apabila narapidana tersebut diberikan {!! $layanan_string !!}*) Narapidana yang bersangkutan tidak melarikan diri dan / atau tidak melakukan perbuatan melanggar hukum lagi.</li>
        <li>Sanggup membantu dalam membimbing, memberikan penghidupan dan turut mengawasi narapidana yang bersangkutan selama mengikuti program {!! $layanan_string !!}*).</li>
        <li>Bahwa selama dalam proses Pengusulan Program {!! $layanan_string !!}*) saya tidak dibebankan biaya pengurusan apapun.</li>
    </ol>

    <div style="margin-top: 5px;">Demikian Surat Jaminan ini dibuat dengan sesungguhnya tanpa paksaan dari pihak lain dan dipergunakan sebagaimana mestinya.</div>

    <!-- TANDA TANGAN -->
    <table class="footer-table">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%;">
                Lamongan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Penjamin,<br>
                <div class="materai-placeholder">
                    MATERAI 10000
                </div>
                ( {{ $data['nama_penjamin'] ?? '.......................' }} )
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" style="padding-top: 10px;">
                Mengetahui,
            </td>
        </tr>
        <tr>
            <td style="width: 50%; padding-top: 10px;">
                Kepala
                <div class="sign-buffer"></div>
                ( ....................................... )
            </td>
            <td style="width: 50%; padding-top: 10px;">
                Kepala Desa/ Lurah
                <div class="sign-buffer"></div>
                ( ....................................... )
            </td>
        </tr>
    </table>
    
    <div class="note">
        Keterangan :<br>
        (*) pilih salah satu sesuai dengan jenis usulan program pembinaan
    </div>
</body>
</html>
