@extends('layouts.admin')

@section('title', 'Manajemen Kunjungan')
@section('page_title', 'Kontrol Kunjungan Lapas')

@section('header_actions')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.laporan.kunjungan.excel', ['date' => $date]) }}" class="btn-compact bg-emerald-600 text-white hover:bg-emerald-700 shadow-md">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Ekspor Excel
    </a>
</div>
@endsection

@section('content')
<!-- Daily Pulse Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded tracking-tighter">Antrean</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pendaftar</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats['total'] }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all">
                <i data-lucide="log-in" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded tracking-tighter">Aktif</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status: MASUK</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats['masuk'] }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                <i data-lucide="log-out" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-red-500 bg-red-50 px-2 py-0.5 rounded tracking-tighter">Selesai</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status: KELUAR</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats['keluar'] }}</p>
    </div>

    <div class="admin-card p-5 bg-midnight-blue border-midnight-blue group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-white/10 text-gold-dignity rounded flex items-center justify-center">
                <i data-lucide="activity" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-bold text-white/50 uppercase tracking-tighter">Periode</span>
        </div>
        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1">Monitoring</p>
        <p class="text-xl font-black text-white">{{ $date ? date('d M Y', strtotime($date)) : 'Semua Data' }}</p>
    </div>
</div>


<!-- Control Panel -->
<div class="admin-card p-6 mb-6 reveal-on-scroll">
    <form action="{{ route('admin.kunjungan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5 ml-1">Pilih Tanggal (Opsional)</label>
            <input type="date" name="date" value="{{ $date }}" placeholder="Semua Tanggal" class="w-full bg-soft-grey border-none py-2 rounded-lg text-[12px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5 ml-1">Filter Status</label>
            <select name="status" class="w-full bg-soft-grey border-none py-2 rounded-lg text-[12px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity appearance-none">
                <option value="">Semua</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Pendaftaran</option>
                <option value="masuk" {{ $status == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ $status == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div class="md:col-span-6">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5 ml-1">Cari Nama / NIK Pengunjung / WBP</label>
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik informasi yang dicari..." class="w-full bg-soft-grey border-none pl-10 pr-4 py-2 rounded-lg text-[12px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity shadow-inner">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            </div>
        </div>
        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="flex-1 btn-compact bg-midnight-blue text-white shadow-md hover:bg-gold-dignity hover:text-midnight-blue">Sinkron</button>
            <a href="{{ route('admin.kunjungan.index') }}" class="btn-compact border border-platinum text-slate-400 hover:bg-soft-grey px-3"><i data-lucide="rotate-ccw" class="w-4 h-4"></i></a>
        </div>
    </form>
</div>

<!-- Data Management -->
<form id="bulkForm" action="{{ route('admin.kunjungan.bulk-delete') }}" method="POST">
    @csrf @method('DELETE')
    <div class="admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                   <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-platinum text-midnight-blue focus:ring-gold-dignity cursor-pointer">
                   <label for="selectAll" class="text-[11px] font-black text-slate-400 uppercase cursor-pointer">Pilih Baris</label>
                </div>
                <button type="submit" id="bulkDeleteBtn" disabled class="btn-compact py-1.5 bg-red-50 text-red-500 border border-red-100 opacity-50 cursor-not-allowed hover:bg-red-500 hover:text-white transition-all shadow-sm">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus Massal
                </button>
            </div>
            <div class="flex items-center gap-2 text-slate-400 bg-soft-grey/50 px-3 py-1.5 rounded-full border border-platinum/50">
                <i data-lucide="mouse-pointer-2" class="w-4 h-4"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Tabel dapat digeser secara horizontal</span>
            </div>
        </div>

        <div class="table-container custom-scrollbar text-midnight-blue bg-white">
            <table class="w-full min-w-[1600px] border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-[11px] font-black uppercase tracking-wider text-slate-600">
                        <th class="border border-slate-300 w-12 text-center py-3 px-2">
                           <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-midnight-blue focus:ring-gold-dignity cursor-pointer">
                        </th>
                        <th class="border border-slate-300 w-24 text-center py-3 px-2">Antrean</th>
                        <th class="border border-slate-300 w-20 text-center py-3 px-2">Jam</th>
                        <th class="border border-slate-300 text-left py-3 px-4 w-60">Pengunjung Utama</th>
                        <th class="border border-slate-300 text-left py-3 px-4 w-44">NIK Utama</th>
                        <th class="border border-slate-300 text-left py-3 px-4 w-40">No. Telepon</th>
                        <th class="border border-slate-300 text-left py-3 px-4">Pengikut / Rombongan</th>
                        <th class="border border-slate-300 text-left py-3 px-4 w-60">Nama WBP</th>
                        <th class="border border-slate-300 text-left py-3 px-4 w-48">Barang Bawaan</th>
                        <th class="border border-slate-300 text-center py-3 px-2 w-28">Status</th>
                        <th class="border border-slate-300 text-center py-3 px-4 w-40">Aksi Kendali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($groupedData as $tanggal => $items)
                    <!-- Date Group Header (Excel Style) -->
                    <tr class="bg-slate-50 font-bold border-y border-slate-300">
                        <td colspan="11" class="px-4 py-2 text-[12px] text-midnight-blue border border-slate-300">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-gold-dignity"></i>
                                <span>TANGGAL: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }} ({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l') }}) — {{ $items->count() }} Data</span>
                            </div>
                        </td>
                    </tr>

                    @foreach($items as $item)
                    <tr class="hover:bg-slate-50 transition-all align-middle text-[12px]">
                        <td class="border border-slate-300 text-center py-3">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="bulk-item w-4 h-4 rounded border-slate-300 text-midnight-blue focus:ring-gold-dignity cursor-pointer">
                        </td>
                        <td class="border border-slate-300 text-center font-black text-midnight-blue py-3 bg-slate-50/50">
                            {{ $item->nomor_antrian ?? '-' }}
                        </td>
                        <td class="border border-slate-300 text-center text-slate-500 py-3">
                            {{ date('H:i', strtotime($item->created_at)) }}
                        </td>
                        <td class="border border-slate-300 px-4 py-3 font-bold group">
                            <div class="flex items-center justify-between">
                                <span class="uppercase">{{ $item->nama_pengunjung }}</span>
                                <button type="button" onclick="copyToClipboard('{{ $item->nama_pengunjung }}', 'Nama')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="copy" class="w-3 h-3 text-slate-300 hover:text-gold-dignity"></i>
                                </button>
                            </div>
                        </td>
                        <td class="border border-slate-300 px-4 py-3 font-mono text-[11px] group">
                            <div class="flex items-center justify-between">
                                <span>{{ $item->nik }}</span>
                                <button type="button" onclick="copyToClipboard('{{ $item->nik }}', 'NIK')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="copy" class="w-3 h-3 text-slate-300 hover:text-gold-dignity"></i>
                                </button>
                            </div>
                        </td>
                        <td class="border border-slate-300 px-4 py-3 group">
                            <div class="flex items-center justify-between text-emerald-700 font-bold">
                                <span>{{ $item->no_telp ?? '-' }}</span>
                                <button type="button" onclick="copyToClipboard('{{ $item->no_telp }}', 'No Telp')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="copy" class="w-3 h-3 text-slate-300 hover:text-gold-dignity"></i>
                                </button>
                            </div>
                        </td>
                        <td class="border border-slate-300 px-4 py-3">
                            @if($item->pengunjung->count() > 1)
                            <div class="flex flex-wrap gap-1">
                                @foreach($item->pengunjung as $idx => $p)
                                    @if($idx > 0)
                                    <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 text-[10px] font-bold text-slate-600 group/flr">
                                        {{ $p->nama_pengunjung }} ({{ $p->hubungan }})
                                        <button type="button" onclick="copyToClipboard('{{ $p->nama_pengunjung }}', 'Nama Pengikut')" class="opacity-0 group-hover/flr:opacity-100"><i data-lucide="copy" class="w-2.5 h-2.5"></i></button>
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                            @else
                            <span class="text-slate-300 italic text-[10px]">Tidak ada pengikut</span>
                            @endif
                        </td>
                        <td class="border border-slate-300 px-4 py-3 font-extrabold text-slate-700 group">
                            <div class="flex items-center justify-between">
                                <span class="uppercase">{{ $item->nama_wbp }}</span>
                                <button type="button" onclick="copyToClipboard('{{ $item->nama_wbp }}', 'Nama WBP')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="copy" class="w-3 h-3 text-slate-300 hover:text-gold-dignity"></i>
                                </button>
                            </div>
                        </td>
                        <td class="border border-slate-300 px-4 py-3 text-slate-600 text-[11px]">
                            {{ $item->barang_bawaan ?? '-' }}
                        </td>
                        <td class="border border-slate-300 text-center py-3">
                            @php
                                $badgeStyle = match($item->status) {
                                    'masuk' => 'bg-emerald-100 text-emerald-800',
                                    'keluar' => 'bg-slate-100 text-slate-500',
                                    default => 'bg-amber-100 text-amber-800'
                                };
                                $statusText = match($item->status) {
                                    'masuk' => 'MASUK',
                                    'keluar' => 'KELUAR',
                                    default => 'ANTRE'
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black {{ $badgeStyle }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="border border-slate-300 px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                @if($item->status == 'approved')
                                <button type="button" onclick="updateStatus('{{ $item->id }}', 'masuk')" title="Proses Masuk" class="w-8 h-8 flex items-center justify-center bg-emerald-500 text-white rounded hover:bg-emerald-600 transition-all">
                                    <i data-lucide="log-in" class="w-4 h-4"></i>
                                </button>
                                @endif

                                @if($item->status == 'masuk')
                                <button type="button" onclick="updateStatus('{{ $item->id }}', 'keluar')" title="Selesai" class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white rounded hover:bg-blue-600 transition-all">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                </button>
                                @endif

                                <a href="{{ route('kunjungan.tiket', $item->id) }}" target="_blank" title="Print Tiket" class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-600 rounded border border-slate-300 hover:bg-midnight-blue hover:text-white transition-all">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>

                                <form action="{{ route('admin.kunjungan.destroy', $item->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded border border-red-200 hover:bg-red-500 hover:text-white transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @empty
                    <tr><td colspan="11" class="py-20 text-center text-slate-400 border border-slate-300 italic">Data tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="px-8 py-6 border-t border-platinum bg-slate-50/50">
            {{ $data->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</form>

<!-- Professional Toast -->
<div id="copyToast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[600] hidden bg-midnight-blue text-white pl-8 pr-12 py-5 rounded-[2rem] shadow-2xl items-center gap-5 border border-white/10 backdrop-blur-lg transform transition-all translate-y-20">
    <div class="w-12 h-12 bg-white text-midnight-blue rounded-2xl flex items-center justify-center shadow-xl transform rotate-12"><i data-lucide="check-check" class="w-6 h-6"></i></div>
    <div>
        <h5 class="text-[11px] font-black uppercase tracking-[0.2em] leading-none mb-1 text-gold-dignity">Data Dialihkan</h5>
        <p id="copyMessage" class="text-sm font-bold text-white"></p>
    </div>
</div>

<script>
    // Status Update Helper
    function updateStatus(id, status) {
        let title = status === 'masuk' ? 'Proses Masuk?' : 'Selesaikan Kunjungan?';
        let text = status === 'masuk' ? 'Pengunjung akan tercatat mulai memasuki area Lapas.' : 'Sesi kunjungan akan diakhiri.';
        
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#002147',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kunjungan/${id}/status/${status}`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Modal Helpers
    function openFollowersModal(id) {
        document.getElementById(`modal-followers-${id}`).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeFollowersModal(id) {
        document.getElementById(`modal-followers-${id}`).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Handle Checkbox Selection
    const selectAll = document.getElementById('selectAll');
    const bulkItems = document.querySelectorAll('.bulk-item');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            bulkItems.forEach(item => item.checked = this.checked);
            toggleBulkBtn();
        });
    }

    bulkItems.forEach(item => {
        item.addEventListener('change', toggleBulkBtn);
    });

    function toggleBulkBtn() {
        const checkedCount = document.querySelectorAll('.bulk-item:checked').length;
        if(checkedCount > 0) {
            bulkDeleteBtn.disabled = false;
            bulkDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            bulkDeleteBtn.classList.add('bg-red-500', 'text-white', 'border-red-600');
        } else {
            bulkDeleteBtn.disabled = true;
            bulkDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            bulkDeleteBtn.classList.remove('bg-red-500', 'text-white', 'border-red-600');
        }
    }



    function copyToClipboard(text, label, isEncoded = false) {
        let content = isEncoded ? decodeURIComponent(text) : text;
        navigator.clipboard.writeText(content).then(() => {
            const toast = document.getElementById('copyToast');
            const msg = document.getElementById('copyMessage');
            
            msg.innerText = label + ' disalin.';
            toast.classList.remove('hidden', 'translate-y-20');
            toast.classList.add('flex', 'translate-y-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex', 'translate-y-0', 'opacity-0');
                }, 400);
            }, 3000);

            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E1E4E8; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #C5A059; }
</style>
@endsection
