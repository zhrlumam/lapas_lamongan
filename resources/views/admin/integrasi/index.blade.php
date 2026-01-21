@extends('layouts.admin')

@section('title', 'Kelola Integrasi')
@section('page_title', 'Administrasi Integrasi')

@section('content')
<div x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        this.selected = this.allSelected ? {{ json_encode($data->pluck('id')) }} : [];
    }
}">
    <!-- Summary Cards (Premium Dashboard Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded tracking-tighter">Database</span>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Usulan</p>
            <p class="text-2xl font-black text-midnight-blue">{{ $stats['total'] }}</p>
        </div>

        <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 bg-soft-grey text-amber-600 rounded flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-white bg-amber-500 px-2 py-0.5 rounded tracking-tighter">Pending</span>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Menunggu Verifikasi</p>
            <p class="text-2xl font-black text-midnight-blue">{{ $stats['pending'] }}</p>
        </div>

        <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 bg-soft-grey text-emerald-600 rounded flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-white bg-emerald-500 px-2 py-0.5 rounded tracking-tighter">Approved</span>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Disetujui</p>
            <p class="text-2xl font-black text-midnight-blue">{{ $stats['approved'] }}</p>
        </div>

        <div class="admin-card p-5 bg-midnight-blue border-midnight-blue group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 bg-white/10 text-gold-dignity rounded flex items-center justify-center">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-white/50 uppercase tracking-tighter">Hari Ini</span>
            </div>
            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1">Masuk Baru</p>
            <p class="text-lg font-black text-white truncate uppercase">+{{ $stats['today'] }} Usulan</p>
        </div>
    </div>

    <!-- Bulk Action Overlay -->
    <div x-show="selected.length > 0" x-transition x-cloak
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-midnight-blue text-white px-6 py-3 rounded shadow-2xl z-50 flex items-center gap-6 border border-white/10">
        <span class="text-[11px] font-bold uppercase tracking-widest"><span x-text="selected.length"></span> Data Terpilih</span>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.integrasi.bulk-status') }}" method="POST" class="inline">
                @csrf
                <template x-for="id in selected">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn-compact bg-emerald-600 border-none text-white hover:bg-emerald-700">Approve</button>
            </form>
            <form action="{{ route('admin.integrasi.bulk-status') }}" method="POST" class="inline">
                @csrf
                <template x-for="id in selected">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn-compact bg-red-600 border-none text-white hover:bg-red-700">Reject</button>
            </form>
            <form action="{{ route('admin.integrasi.bulk-delete') }}" method="POST" class="inline delete-form">
                @csrf
                <template x-for="id in selected">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="btn-compact bg-slate-700 border-none text-white hover:bg-slate-800"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
            </form>
        </div>
        <button @click="selected = []; allSelected = false" class="text-white/40 hover:text-white transition-all"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>

    <!-- Main Content Card -->
    <div class="admin-card overflow-hidden reveal-on-scroll" style="transition-delay: 400ms;">
        <div class="px-6 py-4 border-b border-platinum flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
            <div>
                <h3 class="text-[13px] font-black text-midnight-blue uppercase">Daftar Usulan Mandiri (PB/CB/CMB)</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Monitoring proses pengajuan integrasi online</p>
            </div>
            
            <!-- Compact Filter Buttons -->
            <div class="flex items-center gap-2">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-3 py-1.5 bg-soft-grey text-midnight-blue text-[10px] font-black uppercase tracking-widest rounded border border-platinum hover:bg-midnight-blue hover:text-white transition-all flex items-center gap-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-64 bg-white border border-platinum shadow-xl rounded-lg p-4 z-20">
                         <form method="GET" action="{{ route('admin.integrasi.index') }}" class="space-y-3">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Status</label>
                                <select name="status" class="w-full text-xs border-platinum rounded focus:ring-midnight-blue">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Program</label>
                                <select name="program" class="w-full text-xs border-platinum rounded focus:ring-midnight-blue">
                                    <option value="">Semua</option>
                                    <option value="PB" {{ request('program') == 'PB' ? 'selected' : '' }}>PB</option>
                                    <option value="CB" {{ request('program') == 'CB' ? 'selected' : '' }}>CB</option>
                                    <option value="CMB" {{ request('program') == 'CMB' ? 'selected' : '' }}>CMB</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full py-2 bg-midnight-blue text-white text-[10px] font-black uppercase tracking-widest rounded hover:bg-gold-dignity transition-all">Terapkan</button>
                         </form>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.integrasi.index') }}" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..." class="pl-8 pr-3 py-1.5 text-[11px] border border-platinum rounded focus:ring-1 focus:ring-midnight-blue w-32 focus:w-48 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 group-focus-within:text-midnight-blue"></i>
                </form>
            </div>
        </div>

        <div class="px-0 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="w-10 px-4 py-4 text-center border-b border-platinum">
                            <input type="checkbox" @click="toggleAll()" :checked="allSelected" class="w-3.5 h-3.5 rounded border-platinum text-midnight-blue focus:ring-0 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Pemohon & WBP</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Program</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Status</th>
                        <th class="px-6 py-4 text-right text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-platinum">
                    @forelse($data as $item)
                    <tr class="hover:bg-soft-grey/30 transition-all group" :class="selected.includes({{ $item->id }}) ? 'bg-soft-grey' : ''">
                        <td class="text-center px-4">
                            <input type="checkbox" :value="{{ $item->id }}" x-model="selected" class="w-3.5 h-3.5 rounded border-platinum text-midnight-blue focus:ring-0 cursor-pointer">
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-soft-grey rounded-xl flex items-center justify-center font-black text-xs text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all">
                                    {{ substr($item->nama_wbp, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-sm font-black text-midnight-blue uppercase group-hover:text-gold-dignity transition-colors">{{ $item->nama_wbp }}</span>
                                    <div class="flex flex-col mt-0.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Penjamin: {{ $item->nama_penjamin }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-2 py-1 bg-midnight-blue/5 text-midnight-blue rounded text-[10px] font-extrabold border border-midnight-blue/10">
                                {{ $item->jenis_program }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $badge = match($item->status) {
                                    'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'rejected' => 'bg-red-50 text-red-600 border-red-100',
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    default => 'bg-slate-50 text-slate-500 border-slate-100'
                                };
                                $icon = match($item->status) {
                                    'approved' => 'check-circle',
                                    'rejected' => 'x-circle',
                                    'pending' => 'clock',
                                    default => 'minus'
                                };
                            @endphp
                            <div class="flex flex-col items-center gap-1">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border {{ $badge }}">
                                    <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                                    <span class="text-[9px] font-black uppercase">{{ $item->status }}</span>
                                </div>
                                @if($item->status === 'rejected' && $item->alasan_penolakan)
                                    <p class="text-[9px] text-red-500 font-bold max-w-[120px] truncate" title="{{ $item->alasan_penolakan }}">
                                        Ket: {{ $item->alasan_penolakan }}
                                    </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.integrasi.show', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-slate-500 rounded-xl hover:bg-midnight-blue hover:text-white hover:border-midnight-blue transition-all shadow-sm" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                @if($item->status === 'pending')
                                <form id="approve-form-{{ $item->id }}" action="{{ route('admin.integrasi.status', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="button" onclick="confirmApprove({{ $item->id }}, '{{ $item->nama_wbp }}')" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all shadow-sm" title="Setujui">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                <form id="reject-form-{{ $item->id }}" action="{{ route('admin.integrasi.status', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <input type="hidden" name="alasan_penolakan" id="reason-{{ $item->id }}">
                                    <button type="button" onclick="confirmReject({{ $item->id }}, '{{ $item->nama_wbp }}')" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-red-600 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm" title="Tolak">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @endif
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.integrasi.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_wbp }}')" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-slate-400 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                                    <i data-lucide="file-x" class="w-8 h-8"></i>
                                </div>
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Tidak ada data usulan ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Layout -->
        <div class="md:hidden divide-y divide-platinum bg-white">
            @foreach($data as $item)
            <div class="p-4 space-y-4" :class="selected.includes({{ $item->id }}) ? 'bg-soft-grey' : ''">
                <div class="flex items-center gap-3">
                    <input type="checkbox" :value="{{ $item->id }}" x-model="selected" class="w-4 h-4 rounded border-platinum">
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <span class="font-black text-midnight-blue uppercase text-sm leading-none">{{ $item->nama_wbp }}</span>
                            <span class="text-[9px] font-black text-gold-dignity uppercase bg-gold-dignity/5 px-2 py-0.5 rounded border border-gold-dignity/10">{{ $item->jenis_program }}</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1 uppercase">{{ $item->nama_penjamin }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.integrasi.show', $item->id) }}" class="flex-1 py-2.5 bg-soft-grey text-midnight-blue rounded text-[10px] font-black uppercase tracking-widest text-center border border-platinum hover:bg-midnight-blue hover:text-white transition-all">
                        Detail
                    </a>
                    @if($item->status === 'pending')
                    <button type="button" onclick="confirmApprove({{ $item->id }}, '{{ $item->nama_wbp }}')" class="flex-1 py-2.5 bg-midnight-blue text-white rounded text-[10px] font-black uppercase tracking-widest">Setujui</button>
                    <button type="button" onclick="confirmReject({{ $item->id }}, '{{ $item->nama_wbp }}')" class="flex-1 py-2.5 border border-platinum text-midnight-blue rounded text-[10px] font-black uppercase tracking-widest">Tolak</button>
                    @endif
                    <button type="button" onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_wbp }}')" class="py-2.5 px-3 bg-red-50 text-red-600 rounded border border-red-100 hover:bg-red-600 hover:text-white transition-all">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

@if($data->hasPages())
        <div class="px-6 py-4 bg-soft-grey/30 border-t border-platinum">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function confirmApprove(id, name) {
    Swal.fire({
        title: 'Setujui Pengajuan?',
        text: 'Pengajuan ' + name + ' akan disetujui.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form-' + id).submit();
        }
    });
}

function confirmReject(id, name) {
    Swal.fire({
        title: 'Tolak Pengajuan?',
        html: `
            <p class="mb-4 text-sm text-gray-600">Alasan penolakan untuk <strong>${name}</strong>:</p>
            <textarea id="swal-alasan-${id}" class="swal2-input w-full" rows="4" placeholder="Contoh: NIK tidak valid" style="height: 100px; resize: vertical;"></textarea>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        preConfirm: () => {
            const alasan = document.getElementById('swal-alasan-' + id).value;
            if (!alasan || alasan.trim() === '') {
                Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                return false;
            }
            return alasan;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reason-' + id).value = result.value;
            document.getElementById('reject-form-' + id).submit();
        }
    });
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data pengajuan ' + name + ' akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
