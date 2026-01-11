@extends('layouts.admin')

@section('title', 'Detail Integrasi')
@section('page_title', 'Detail Pengajuan Integrasi')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.integrasi.index') }}" class="inline-flex items-center gap-2 text-midnight-blue hover:text-gold-dignity transition-colors text-sm font-bold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Main Card -->
    <div class="admin-card overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-[13px] font-black text-midnight-blue uppercase">Detail Pengajuan</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">ID: #{{ $item->id }} - {{ $item->created_at->format('d M Y H:i') }}</p>
                </div>
                @php
                    $badge = match($item->status) {
                        'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'rejected' => 'bg-red-50 text-red-600 border-red-100',
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        default => 'bg-slate-50 text-slate-500 border-slate-100'
                    };
                @endphp
                <span class="px-3 py-1.5 rounded border text-[10px] font-black uppercase {{ $badge }}">
                    {{ $item->status }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Data WBP -->
            <div class="bg-soft-grey/30 rounded-lg p-5 border border-platinum">
                <h4 class="text-[11px] font-black text-midnight-blue uppercase mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-gold-dignity"></i>
                    Data Warga Binaan Pemasyarakatan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Nama WBP</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ $item->nama_wbp }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Perkara</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ $item->perkara }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Jenis Program</label>
                        <span class="inline-block px-3 py-1 bg-midnight-blue/5 text-midnight-blue rounded text-[10px] font-extrabold border border-midnight-blue/10">
                            {{ $item->jenis_program }}
                        </span>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Tanggal Pengajuan</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Penjamin -->
            <div class="bg-soft-grey/30 rounded-lg p-5 border border-platinum">
                <h4 class="text-[11px] font-black text-midnight-blue uppercase mb-4 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-gold-dignity"></i>
                    Data Penjamin
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Nama Penjamin</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ $item->nama_penjamin }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">NIK Penjamin</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ $item->nik_penjamin }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Alamat</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ $item->alamat_penjamin }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Telepon</label>
                        <p class="text-[13px] font-black text-midnight-blue">{{ $item->telepon_penjamin }}</p>
                    </div>
                </div>
            </div>

            <!-- File Surat -->
            @if($item->file_surat)
            <div class="bg-soft-grey/30 rounded-lg p-5 border border-platinum">
                <h4 class="text-[11px] font-black text-midnight-blue uppercase mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-gold-dignity"></i>
                    File Surat Jaminan
                </h4>
                <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-midnight-blue text-white rounded text-[10px] font-black uppercase tracking-widest hover:bg-gold-dignity transition-all">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download Surat
                </a>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-platinum">
                @if($item->status === 'pending')
                <form action="{{ route('admin.integrasi.status', $item->id) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded text-[11px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Setujui Pengajuan
                    </button>
                </form>
                <form action="{{ route('admin.integrasi.status', $item->id) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="w-full py-3 bg-red-600 text-white rounded text-[11px] font-black uppercase tracking-widest hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        Tolak Pengajuan
                    </button>
                </form>
                @endif
                <form action="{{ route('admin.integrasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-slate-600 text-white rounded text-[11px] font-black uppercase tracking-widest hover:bg-slate-700 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Timeline/History (Optional - untuk future enhancement) -->
    <div class="admin-card overflow-hidden">
        <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30">
            <h3 class="text-[13px] font-black text-midnight-blue uppercase">Riwayat Perubahan</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-midnight-blue/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="plus" class="w-4 h-4 text-midnight-blue"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-black text-midnight-blue">Data Dibuat</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $item->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
                @if($item->updated_at != $item->created_at)
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-gold-dignity/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="edit" class="w-4 h-4 text-gold-dignity"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-black text-midnight-blue">Terakhir Diperbarui</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $item->updated_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
