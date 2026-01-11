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
        <p class="text-xl font-black text-white">{{ date('d M Y', strtotime($date)) }}</p>
    </div>
</div>


<!-- Control Panel -->
<div class="admin-card p-6 mb-6 reveal-on-scroll">
    <form action="{{ route('admin.kunjungan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5 ml-1">Pilih Hari</label>
            <input type="date" name="date" value="{{ $date }}" class="w-full bg-soft-grey border-none py-2 rounded-lg text-[12px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity">
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

        <div class="table-container custom-scrollbar text-midnight-blue">
            <table class="w-full min-w-[1300px]">
                <thead>
                    <tr class="bg-soft-grey/40">
                        <th class="w-16"></th>
                        <th class="w-20 text-center text-[10px] font-black uppercase tracking-widest text-slate-400 py-4">Antrean</th>
                        <th class="w-72 text-left text-[10px] font-black uppercase tracking-widest text-slate-400 py-4">Detail Pengunjung</th>
                        <th class="w-64 text-left text-[10px] font-black uppercase tracking-widest text-slate-400 py-4">Nama WBP</th>
                        <th class="w-48 text-left text-[10px] font-black uppercase tracking-widest text-slate-400 py-4">Barang Bawaan</th>
                        <th class="w-40 text-center text-[10px] font-black uppercase tracking-widest text-slate-400 py-4">Status</th>
                        <th class="text-right pr-8 text-[10px] font-black uppercase tracking-widest text-slate-400 py-4">Modul Kendali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-platinum/50">
                    @forelse($data as $item)
                    @php
                        $summary = "DATA REGISTRASI KUNJUNGAN ONLINE\n";
                        $summary .= "-----------------------------------\n";
                        $summary .= "No. Antrean: " . ($item->nomor_antrian ?? '-') . "\n";
                        $summary .= "Nama: " . strtoupper($item->nama_pengunjung) . "\n";
                        $summary .= "NIK: " . $item->nik . "\n";
                        $summary .= "No. Telp: " . ($item->no_telp ?? '-') . "\n";
                        $summary .= "Alamat: " . ($item->alamat ?? '-') . "\n";
                        $summary .= "WBP: " . strtoupper($item->nama_wbp) . "\n";
                        $summary .= "Tanggal: " . date('d/m/Y', strtotime($item->tanggal_kunjungan)) . "\n";
                        $summary .= "Sesi: " . $item->waktu_kunjungan . "\n";
                        if($item->barang_bawaan) $summary .= "Barang: " . $item->barang_bawaan . "\n";
                        if($item->pengunjung->count() > 1) {
                            $summary .= "\nKeluarga Pengikut:\n";
                            foreach($item->pengunjung as $idx => $p) {
                                if($idx > 0) $summary .= "- " . strtoupper($p->nama_pengunjung) . " (" . $p->hubungan . ")\n";
                            }
                        }
                        $summary .= "-----------------------------------";
                    @endphp
                    <tr class="hover:bg-soft-grey/20 transition-all align-top group">
                        <td class="pl-8 pt-6">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="bulk-item w-5 h-5 rounded border-platinum text-midnight-blue focus:ring-gold-dignity cursor-pointer">
                        </td>
                        <td class="text-center pt-6">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-soft-grey text-midnight-blue rounded group-hover:bg-midnight-blue group-hover:text-white transition-all font-black text-sm shadow-sm">{{ $item->nomor_antrian ?? '-' }}</div>
                        </td>
                        <td class="pt-6 px-4">
                            <div class="flex flex-col gap-2">
                                <span class="text-sm font-black text-midnight-blue uppercase leading-tight group-hover:text-gold-dignity transition-colors">{{ $item->nama_pengunjung }}</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" onclick="copyToClipboard('{{ $item->nik }}', 'NIK')" class="text-[9px] font-bold text-slate-500 bg-white border border-platinum px-2 py-0.5 rounded hover:border-midnight-blue hover:text-midnight-blue transition-all">NIK: {{ $item->nik }}</button>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_telp) }}" target="_blank" class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded tracking-tighter hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3 h-3"></i> WA AKTIF
                                    </a>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium truncate max-w-[250px]">{{ $item->alamat ?? '-' }}</span>
                                <div class="mt-2">
                                    <button type="button" onclick="copyToClipboard('{{ rawurlencode($summary) }}', 'Informasi Lengkap', true)" class="flex items-center gap-2 text-[9px] font-black bg-midnight-blue text-white px-3 py-1.5 rounded-lg hover:bg-gold-dignity hover:text-midnight-blue transition-all uppercase tracking-widest shadow-sm">
                                        <i data-lucide="copy" class="w-3 h-3"></i> Salin Data
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="pt-6">
                            <div class="flex flex-col gap-2">
                                <div class="bg-soft-grey p-3 rounded-xl border border-platinum/50">
                                    <span class="text-[13px] font-extrabold text-slate-700 uppercase leading-snug">{{ $item->nama_wbp }}</span>
                                </div>
                                @if($item->pengunjung->count() > 1)
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-black text-slate-400 bg-white px-2 py-1 border border-platinum rounded-full">+{{ $item->pengunjung->count() - 1 }} Pengikut</span>
                                    <button type="button" onclick="openFollowersModal('{{ $item->id }}')" class="text-[9px] font-black text-midnight-blue hover:text-gold-dignity uppercase tracking-widest group/btn flex items-center gap-1 transition-all">
                                        LIHAT <i data-lucide="chevron-right" class="w-3 h-3 group-hover/btn:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="flex items-center gap-2 text-[9px] font-black bg-amber-50 text-amber-600 w-fit px-2.5 py-1 rounded-lg border border-amber-100 uppercase tracking-tighter">
                                    <i data-lucide="clock" class="w-3 h-3"></i> {{ $item->waktu_kunjungan }}
                                </div>
                            </div>
                        </td>
                        <td class="pt-6">
                            @if($item->barang_bawaan)
                            <div class="p-3 bg-soft-grey/30 border border-platinum rounded-lg">
                                <p class="text-[11px] font-bold text-slate-600 leading-relaxed">{{ $item->barang_bawaan }}</p>
                            </div>

                            @else
                            <div class="py-4 border border-platinum/30 rounded-lg flex items-center justify-center opacity-20 bg-soft-grey/20">
                                <i data-lucide="package" class="w-4 h-4 text-slate-400"></i>
                            </div>

                            @endif
                        </td>
                        <td class="pt-6">
                            <div class="flex flex-col gap-2">
                                @php
                                    $badgeStyle = match($item->status) {
                                        'masuk' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'keluar' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-amber-50 text-amber-600 border-amber-100'
                                    };
                                    $statusText = match($item->status) {
                                        'masuk' => 'MASUK',
                                        'keluar' => 'KELUAR',
                                        default => 'ANTRE'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter text-center {{ $badgeStyle }}">
                                    {{ $statusText }}
                                </span>

                                @if($item->check_in_at)
                                <span class="text-[8px] font-black text-slate-400 text-center uppercase">In: {{ date('H:i', strtotime($item->check_in_at)) }}</span>
                                @endif
                                @if($item->check_out_at)
                                <span class="text-[8px] font-black text-slate-400 text-center uppercase">Out: {{ date('H:i', strtotime($item->check_out_at)) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-right pt-6 pr-8">
                            <div class="flex flex-col items-end gap-3">
                                <div class="flex items-center gap-2">
                                    {{-- Jika Status ANTRE (Proses Masuk) --}}
                                    @if($item->status == 'approved')
                                    <button type="button" onclick="updateStatus('{{ $item->id }}', 'masuk')" title="PROSES MASUK" class="group/action flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100/50">
                                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                        <span class="text-[9px] font-black uppercase">Masuk</span>
                                    </button>
                                    @endif

                                    {{-- Jika Status MASUK (Sedang Berkunjung) --}}
                                    @if($item->status == 'masuk')
                                    <button type="button" onclick="updateStatus('{{ $item->id }}', 'keluar')" title="KLIK SELESAI" class="group/action flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all border border-blue-100/50">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                        <span class="text-[9px] font-black uppercase">Selesai</span>
                                    </button>
                                    @endif

                                    {{-- Jika Status KELUAR (Sudah Selesai) --}}
                                    @if($item->status == 'keluar')
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg border border-platinum opacity-60">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        <span class="text-[9px] font-black uppercase">Tuntas</span>
                                    </div>
                                    @endif
                                    <button type="button" onclick="confirmDelete(this)" data-id="{{ $item->id }}" class="w-9 h-9 bg-slate-50 text-slate-400 rounded hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center border border-platinum">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>

                                </div>
                                
                                <a href="{{ route('kunjungan.tiket', $item->id) }}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-2 bg-soft-grey text-midnight-blue text-[9px] font-black rounded-lg border border-platinum/50 hover:bg-midnight-blue hover:text-white transition-all uppercase shadow-sm">
                                    <i data-lucide="printer" class="w-3.5 h-3.5"></i> CETAK TIKET
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Followers Modal Container -->
                    <div id="modal-followers-{{ $item->id }}" class="fixed inset-0 z-[500] hidden overflow-y-auto">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="fixed inset-0 bg-midnight-blue/40 backdrop-blur-md" onclick="closeFollowersModal('{{ $item->id }}')"></div>
                            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden p-8 border border-platinum/50">
                                <div class="flex justify-between items-center mb-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-midnight-blue text-gold-dignity rounded-xl flex items-center justify-center shadow-lg"><i data-lucide="users" class="w-5 h-5"></i></div>
                                        <h3 class="text-xl font-black text-midnight-blue uppercase">Daftar Rombongan</h3>
                                    </div>
                                    <button type="button" onclick="closeFollowersModal('{{ $item->id }}')" class="w-10 h-10 bg-soft-grey text-slate-400 rounded-full hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-all"><i data-lucide="x" class="w-6 h-6"></i></button>
                                </div>
                                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($item->pengunjung as $p)
                                    <div class="p-5 bg-soft-grey/30 rounded-3xl border border-platinum/40 flex justify-between items-center group/item hover:bg-white hover:shadow-xl hover:border-gold-dignity/20 transition-all duration-300">
                                        <div>
                                            <p class="text-sm font-black text-midnight-blue uppercase group-hover/item:text-gold-dignity transition-colors">{{ $p->nama_pengunjung }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $p->nik_pengunjung }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $p->jk }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-4 py-1.5 bg-white text-midnight-blue text-[10px] font-black rounded-full border border-platinum group-hover/item:border-gold-dignity/30 shadow-sm uppercase">{{ $p->hubungan }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-8">
                                    <button type="button" onclick="closeFollowersModal('{{ $item->id }}')" class="w-full py-5 bg-midnight-blue text-white rounded-2xl font-black uppercase tracking-[0.2em] hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-xl active:scale-95">Selesai Memeriksa</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="7" class="py-32 text-center">
                        <div class="flex flex-col items-center justify-center opacity-30">
                            <i data-lucide="clipboard" class="w-24 h-24 mb-6 text-slate-200"></i>
                            <p class="text-2xl font-black uppercase tracking-[0.5em] text-slate-300">Data Nihil</p>
                        </div>
                    </td></tr>
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
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/kunjungan/${id}/status/${status}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        `;
        document.body.appendChild(form);
        form.submit();
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

    function confirmDelete(btn) {
        const id = btn.getAttribute('data-id');
        Swal.fire({
            title: 'Hapus Pendaftaran?',
            text: "Data kunjungan dan seluruh rombongan akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#002147',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kunjungan/${id}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
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
