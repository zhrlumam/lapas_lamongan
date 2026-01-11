@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Sistem')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded tracking-tighter">+12%</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Berita</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $totalBerita }}</p>
    </div>
    
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded tracking-tighter">Lengkap</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total WBP</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $totalWbp }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="star" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-2 py-0.5 rounded tracking-tighter">IKM</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Indeks Kepuasan</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $skorIkm }}</p>
    </div>

    <div class="admin-card p-5 bg-midnight-blue border-midnight-blue group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-white/10 text-gold-dignity rounded flex items-center justify-center">
                <i data-lucide="activity" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-bold text-white/50 uppercase tracking-tighter">Hari Ini</span>
        </div>
        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1 uppercase">Pengunjung</p>
        <p class="text-2xl font-black text-white">+{{ $pengunjungHariIni }}</p>
    </div>
</div>

<!-- Analytics Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 admin-card p-6 reveal-on-scroll" style="transition-delay: 400ms;">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Tren Kunjungan (7 Hari Terakhir)</h3>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-midnight-blue rounded-full"></div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase">Volume Antrean</span>
            </div>
        </div>
        <div class="h-[250px] w-full">
            <canvas id="visitChart"></canvas>
        </div>
    </div>

    <div class="admin-card p-6 reveal-on-scroll" style="transition-delay: 500ms;">
        <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest mb-6 text-center">Distribusi Pengaduan</h3>
        <div class="h-[200px] w-full relative">
            <canvas id="complaintChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2">
            @foreach($complaintStats as $stat)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-soft-grey rounded">
                    <div class="w-2 h-2 rounded-full {{ 
                        $stat->status == 'Selesai' ? 'bg-emerald-400' : 
                        ($stat->status == 'Diproses' ? 'bg-amber-400' : 'bg-indigo-400') 
                    }}"></div>
                    <span class="text-[10px] font-black text-midnight-blue uppercase">{{ $stat->status }} ({{ $stat->total }})</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum flex justify-between items-center">
            <h3 class="text-[13px] font-black text-midnight-blue uppercase">Laporan Pengaduan Terbaru (Petugas: {{ Auth::user()->nama }})</h3>
            <a href="{{ route('admin.pengaduan.index') }}" class="text-[10px] font-bold text-gold-dignity uppercase tracking-widest hover:text-midnight-blue transition-colors">Semua Laporan →</a>
        </div>
        <div class="p-0">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">Tiket</th>
                        <th class="text-left">Pelapor</th>
                        <th class="text-left">Judul Laporan</th>
                        <th class="text-right whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentComplaints as $complaint)
                    <tr class="hover:bg-soft-grey/50 cursor-pointer" onclick="window.location='{{ route('admin.pengaduan.show', $complaint->id) }}'">
                        <td class="text-slate-400 text-[11px] font-black">#{{ $complaint->kode_tiket }}</td>
                        <td class="font-bold text-midnight-blue text-sm">{{ \Illuminate\Support\Str::limit($complaint->nama_pelapor, 20) }}</td>
                        <td class="text-slate-600 text-[13px] italic line-clamp-1">{{ \Illuminate\Support\Str::limit($complaint->judul_pengaduan ?? 'Tanpa Judul', 30) }}</td>
                        <td class="text-right">
                            @php
                                $statusBadge = match($complaint->status) {
                                    'Selesai' => 'bg-emerald-50 text-emerald-600',
                                    'Diproses' => 'bg-amber-50 text-amber-600',
                                    'Masuk' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                    default => 'bg-slate-100 text-slate-500'
                                };
                            @endphp
                            <span class="px-2 py-0.5 {{ $statusBadge }} text-[9px] font-black rounded uppercase tracking-tighter">{{ $complaint->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-20">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-12 h-12 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                                    <i data-lucide="message-square" class="w-6 h-6"></i>
                                </div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Belum ada laporan masuk</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($recentComplaints->count() > 0)
            <div class="p-6 text-center border-t border-platinum">
                <a href="{{ route('admin.pengaduan.index') }}" class="text-[11px] font-black text-gold-dignity uppercase tracking-widest hover:text-midnight-blue transition-colors">
                    Lihat Semua Pengaduan WBS →
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="admin-card p-6 text-center">
            <h3 class="text-[13px] font-black text-midnight-blue uppercase mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('admin.berita.create') }}" class="btn-compact justify-center bg-midnight-blue text-white hover:bg-gold-dignity hover:text-midnight-blue">
                    <i data-lucide="plus" class="w-3 h-3"></i> Tulis Berita Baru
                </a>
                <a href="{{ route('admin.hunian.index') }}" class="btn-compact justify-center border border-platinum text-midnight-blue hover:bg-soft-grey">
                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> Sync Data Hunian
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="btn-compact justify-center border border-platinum text-midnight-blue hover:bg-soft-grey">
                    <i data-lucide="file-down" class="w-3 h-3"></i> Download Rekap
                </a>
            </div>
        </div>

        <div class="admin-card p-6 bg-soft-grey border-dashed border-2 border-platinum">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-midnight-blue shadow-sm">
                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                </div>
                <h4 class="text-[12px] font-black text-midnight-blue uppercase">Butuh Bantuan?</h4>
            </div>
            <p class="text-[12px] text-slate-500 leading-relaxed mb-4">Hubungi tim IT untuk masalah sinkronisasi database atau reset password petugas.</p>
            <a href="#" class="text-[10px] font-black text-midnight-blue uppercase tracking-widest border-b border-midnight-blue pb-0.5">Kontak Support</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Visitor Trend Chart
        const visitCtx = document.getElementById('visitChart').getContext('2d');
        new Chart(visitCtx, {
            type: 'line',
            data: {
                labels: @json($visitLabels),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: @json($visitData),
                    borderColor: '#002147',
                    backgroundColor: 'rgba(0, 33, 71, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#C5A059',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' } }
                    }
                }
            }
        });

        // Complaint Distribution Chart
        const complaintCtx = document.getElementById('complaintChart').getContext('2d');
        const complaintLabels = @json($complaintStats->pluck('status'));
        const complaintTotals = @json($complaintStats->pluck('total'));
        
        new Chart(complaintCtx, {
            type: 'doughnut',
            data: {
                labels: complaintLabels,
                datasets: [{
                    data: complaintTotals,
                    backgroundColor: ['#4F46E5', '#F59E0B', '#10B981'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '75%'
            }
        });
    });
</script>
@endsection
