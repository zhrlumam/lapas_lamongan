@extends('layouts.admin')

@section('title', 'Kelola Hunian')
@section('page_title', 'Update Kapasitas & Penghuni')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Form Update -->
    <div class="lg:col-span-4">
        <div class="admin-card p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-[13px] font-black text-midnight-blue uppercase">Update Hunian</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Data harian terbaru</p>
                </div>
            </div>

            <form action="{{ route('admin.hunian.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tahanan</label>
                        <input type="number" name="tahanan" value="{{ $latest->tahanan ?? 0 }}" required class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Narapidana</label>
                        <input type="number" name="narapidana" value="{{ $latest->narapidana ?? 0 }}" required class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sidang</label>
                        <input type="number" name="sidang" value="{{ $latest->sidang ?? 0 }}" required class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Berobat</label>
                        <input type="number" name="berobat_luar" value="{{ $latest->berobat_luar ?? 0 }}" required class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full btn-compact bg-midnight-blue text-white hover:bg-gold-dignity hover:text-midnight-blue mt-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Data
                </button>
            </form>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="lg:col-span-8">
        <div class="admin-card overflow-hidden">
            <div class="px-6 py-4 border-b border-platinum flex justify-between items-center bg-slate-50/50">
                <h3 class="text-[13px] font-black text-midnight-blue uppercase">Riwayat Update</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kapasitas: 450</span>
            </div>

            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Tanggal</th>
                            <th class="text-center">Total</th>
                            <th class="text-right">Rincian (T|N|S|B)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hunian as $h)
                        <tr class="hover:bg-soft-grey transition-colors">
                            <td class="font-bold text-midnight-blue text-xs">
                                {{ \Carbon\Carbon::parse($h->tanggal_update)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                <span class="bg-midnight-blue text-white text-[10px] font-black px-2 py-0.5 rounded">
                                    {{ $h->total_penghuni }}
                                </span>
                            </td>
                            <td class="text-right text-[11px] font-bold text-slate-500">
                                <span class="text-midnight-blue">{{ $h->tahanan }}</span> / 
                                <span class="text-midnight-blue">{{ $h->narapidana }}</span> / 
                                <span class="text-amber-600">{{ $h->sidang }}</span> / 
                                <span class="text-red-500">{{ $h->berobat_luar }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-slate-400 text-xs font-bold uppercase tracking-widest">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
