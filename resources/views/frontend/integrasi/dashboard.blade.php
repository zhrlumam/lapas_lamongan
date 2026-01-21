@extends('layouts.app')

@section('title', 'Dashboard Layanan Mandiri')

@section('content')
    <!-- Header Section -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Portal Penjamin</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Layanan Mandiri Integrasi</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Pilih jenis program pembinaan yang akan diajukan untuk Warga Binaan Pemasyarakatan.
            </p>
            <div class="mt-8 flex justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-soft-grey text-midnight-blue rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-platinum transition-all group">
                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>

    <!-- Dashboard Menu -->
    <section class="bg-soft-grey py-20 px-4 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            {{-- Status Notifications --}}
            @if(isset($recentStatusChanges) && $recentStatusChanges->count() > 0)
            <div class="mb-8 space-y-4">
                @foreach($recentStatusChanges->take(3) as $change)
                    @if($change->status === 'approved')
                    <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-green-700 uppercase tracking-wide mb-1">Pengajuan Disetujui</h4>
                            <p class="text-xs text-green-600 font-bold">
                                Pengajuan <strong>{{ $change->jenis_program }}</strong> untuk <strong>{{ $change->nama_wbp }}</strong> telah disetujui oleh Admin.
                            </p>
                            <p class="text-[10px] text-green-500 mt-2">
                                <i data-lucide="clock" class="w-3 h-3 inline"></i> {{ $change->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @elseif($change->status === 'rejected')
                    <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="x-circle" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-red-700 uppercase tracking-wide mb-1">Pengajuan Ditolak</h4>
                            <p class="text-xs text-red-600 font-bold">
                                Pengajuan <strong>{{ $change->jenis_program }}</strong> untuk <strong>{{ $change->nama_wbp }}</strong> ditolak oleh Admin.
                            </p>
                            
                            {{-- Tampilkan alasan penolakan --}}
                            @if($change->alasan_penolakan)
                            <div class="mt-3 p-3 bg-red-100 rounded-lg border border-red-200">
                                <p class="text-[9px] font-bold text-red-700 mb-1">Alasan Penolakan:</p>
                                <p class="text-xs text-red-600 leading-relaxed">{{ $change->alasan_penolakan }}</p>
                            </div>
                            @endif
                            
                            <div class="mt-3 flex items-center gap-3">
                                <p class="text-[10px] text-red-500">
                                    <i data-lucide="clock" class="w-3 h-3 inline"></i> {{ $change->updated_at->diffForHumans() }}
                                </p>
                                <a href="{{ route('integrasi.form', ['type' => $change->jenis_program]) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-red-600 text-white rounded-lg text-[9px] font-black uppercase hover:bg-red-700 transition-all">
                                    <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                    Ajukan Ulang
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endif

            {{-- Status Statistics Cards --}}
            @if(isset($statusCounts))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Pending Card -->
                <div class="bg-white rounded-2xl p-6 border-2 border-yellow-100 shadow-soft">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                            <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                        </div>
                        <span class="text-3xl font-black text-yellow-600">{{ $statusCounts['pending'] }}</span>
                    </div>
                    <h3 class="text-xs font-black text-midnight-blue uppercase tracking-wide">Menunggu Verifikasi</h3>
                    <p class="text-[10px] text-dark-grey mt-1">Sedang diproses Admin</p>
                </div>

                <!-- Approved Card -->
                <div class="bg-white rounded-2xl p-6 border-2 border-green-100 shadow-soft">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                        </div>
                        <span class="text-3xl font-black text-green-600">{{ $statusCounts['approved'] }}</span>
                    </div>
                    <h3 class="text-xs font-black text-midnight-blue uppercase tracking-wide">Disetujui</h3>
                    <p class="text-[10px] text-dark-grey mt-1">Pengajuan berhasil</p>
                </div>

                <!-- Rejected Card -->
                <div class="bg-white rounded-2xl p-6 border-2 border-red-100 shadow-soft">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                            <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <span class="text-3xl font-black text-red-600">{{ $statusCounts['rejected'] }}</span>
                    </div>
                    <h3 class="text-xs font-black text-midnight-blue uppercase tracking-wide">Ditolak</h3>
                    <p class="text-[10px] text-dark-grey mt-1">Tidak memenuhi syarat</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Cuti Bersyarat (CB) -->
                <a href="{{ route('integrasi.form', ['type' => 'Cuti Bersyarat']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">CB</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Cuti Bersyarat</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Pidana di bawah 1 Tahun 6 Bulan</p>
                </a>

                <!-- Pembebasan Bersyarat (PB) -->
                <a href="{{ route('integrasi.form', ['type' => 'Pembebasan Bersyarat']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">PB</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Pembebasan Bersyarat</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Telah menjalani 2/3 masa pidana</p>
                </a>

                <!-- Cuti Menjelang Bebas (CMB) -->
                <a href="{{ route('integrasi.form', ['type' => 'Cuti Menjelang Bebas']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">CMB</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Cuti Menjelang Bebas</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Sisa pidana maksimal 6 bulan</p>
                </a>

                <!-- Asimilasi Kerja Sosial -->
                <a href="{{ route('integrasi.form', ['type' => 'Asimilasi Kerja Sosial']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">AKS</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Asimilasi Kerja Sosial</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Kerja sosial di luar Lapas</p>
                </a>

                <!-- Asimilasi Pihak Ketiga -->
                <a href="{{ route('integrasi.form', ['type' => 'Asimilasi Pihak Ketiga']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">APK</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Asimilasi Pihak Ketiga</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Asimilasi dengan pihak ketiga</p>
                </a>


            </div>

            <!-- Recent Submissions Section -->
            @if(isset($recentSubmissions) && $recentSubmissions->count() > 0)
            <div class="mt-12 bg-white rounded-2xl border border-platinum shadow-soft overflow-hidden">
                <div class="px-8 py-5 border-b border-platinum bg-soft-grey/30 flex justify-between items-center">
                    <h3 class="text-sm font-black text-midnight-blue uppercase">Riwayat Pengajuan Anda</h3>
                    <span class="text-[10px] text-dark-grey/60 font-bold uppercase">{{ $recentSubmissions->count() }} Pengajuan Terakhir</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-soft-grey/50">
                                <th class="px-8 py-4 text-[10px] font-black text-midnight-blue uppercase tracking-widest border-b border-platinum">Warga Binaan</th>
                                <th class="px-8 py-4 text-[10px] font-black text-midnight-blue uppercase tracking-widest border-b border-platinum">Layanan</th>
                                <th class="px-8 py-4 text-[10px] font-black text-midnight-blue uppercase tracking-widest border-b border-platinum">Tanggal</th>
                                <th class="px-8 py-4 text-[10px] font-black text-midnight-blue uppercase tracking-widest border-b border-platinum">Status</th>
                                <th class="px-8 py-4 text-[10px] font-black text-midnight-blue uppercase tracking-widest border-b border-platinum">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-platinum">
                            @foreach($recentSubmissions as $sub)
                            <tr class="hover:bg-soft-grey/30 transition-colors">
                                <td class="px-8 py-4">
                                    <p class="text-xs font-black text-midnight-blue uppercase">{{ $sub->nama_wbp }}</p>
                                    <p class="text-[9px] text-dark-grey mt-0.5">Perkara: {{ $sub->perkara }}</p>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-2 py-1 bg-midnight-blue/5 text-midnight-blue rounded text-[9px] font-black uppercase">{{ $sub->jenis_program }}</span>
                                </td>
                                <td class="px-8 py-4 text-[10px] font-bold text-dark-grey">
                                    {{ $sub->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                            'approved' => 'bg-green-50 text-green-600 border-green-100',
                                            'rejected' => 'bg-red-50 text-red-600 border-red-100'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full border {{ $statusClasses[$sub->status] ?? $statusClasses['pending'] }} text-[9px] font-black uppercase tracking-wider">
                                        {{ $statusLabels[$sub->status] ?? $sub->status }}
                                    </span>
                                    
                                    {{-- Tampilkan alasan penolakan jika ditolak --}}
                                    @if($sub->status === 'rejected' && $sub->alasan_penolakan)
                                    <div class="mt-2 p-2 bg-red-50 rounded-lg border border-red-100">
                                        <p class="text-[9px] font-bold text-red-700 mb-1">Alasan Penolakan:</p>
                                        <p class="text-[9px] text-red-600">{{ $sub->alasan_penolakan }}</p>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-8 py-4">
                                    @if($sub->status === 'approved')
                                        {{-- Tombol Download PDF untuk yang approved --}}
                                        <a href="{{ route('integrasi.download.pdf', $sub->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-[9px] font-black uppercase tracking-wide hover:bg-green-700 transition-all shadow-sm hover:shadow-md">
                                            <i data-lucide="download" class="w-3 h-3"></i>
                                            Download PDF
                                        </a>
                                        <p class="text-[8px] text-green-600 mt-2 leading-tight">
                                            ✓ Cetak, tanda tangan + materai 10.000, kirim via Pos
                                        </p>
                                    @elseif($sub->status === 'rejected')
                                        {{-- Tombol Ajukan Ulang untuk yang ditolak --}}
                                        <a href="{{ route('integrasi.form', ['type' => $sub->jenis_program]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-[9px] font-black uppercase tracking-wide hover:bg-red-700 transition-all shadow-sm hover:shadow-md">
                                            <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                            Ajukan Ulang
                                        </a>
                                    @else
                                        {{-- Status pending --}}
                                        <span class="text-[9px] text-yellow-600 font-bold">
                                            <i data-lucide="clock" class="w-3 h-3 inline"></i>
                                            Menunggu verifikasi Admin
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Additional Help -->
            <div class="mt-12 bg-white rounded-2xl p-8 border border-platinum flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-4">
                     <div class="w-10 h-10 bg-midnight-blue text-white rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-midnight-blue uppercase tracking-wide">Butuh Bantuan?</h4>
                        <p class="text-xs text-dark-grey">Hubungi petugas jika Anda bingung memilih layanan.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-platinum text-midnight-blue rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-dark-grey/10 transition-all flex items-center gap-2">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        Beranda
                    </a>
                    <a href="{{ route('pengaduan') }}" class="px-6 py-3 bg-soft-grey text-midnight-blue rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-platinum transition-all">
                        Hubungi Petugas
                    </a>
                    <form action="{{ route('integrasi.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-red-50 text-red-600 rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-red-100 transition-all">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
