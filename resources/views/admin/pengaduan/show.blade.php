@extends('layouts.admin')

@section('title', 'Detail Pengaduan')
@section('page_title', 'Detail Laporan #' . $item->kode_tiket)

@section('content')
<!-- Mini Stats / Context Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all">
        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">ID Laporan</label>
        <div class="flex items-center gap-3">
            <span class="text-xl font-black text-midnight-blue">#{{ $item->kode_tiket }}</span>
            <span class="px-2 py-0.5 bg-midnight-blue/5 text-midnight-blue text-[8px] font-black rounded uppercase border border-midnight-blue/10">WEBSITE</span>
        </div>
    </div>
    
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all">
        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Status Sekarang</label>
        @php
            $badgeColor = match($item->status) {
                'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                'Diproses' => 'bg-amber-50 text-amber-600 border-amber-100',
                'Masuk' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                default => 'bg-slate-50 text-slate-500 border-slate-100'
            };
        @endphp
        <span class="px-3 py-1 rounded-xl border text-[10px] font-black uppercase tracking-widest {{ $badgeColor }}">
            {{ $item->status }}
        </span>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all">
        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Interaksi</label>
        <div class="flex items-center gap-2 text-midnight-blue font-black">
            <i data-lucide="message-circle" class="w-4 h-4 text-gold-dignity"></i>
            {{ $item->balasan->count() }} Pesan
        </div>
    </div>

    <div class="admin-card p-5 group hover:border-indigo-950 transition-all bg-indigo-950 text-white">
        <label class="block text-[9px] font-black text-white/40 uppercase tracking-widest mb-2">Waktu Respon</label>
        <div class="text-sm font-bold truncate">
            {{ $item->created_at->diffForHumans() }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Kolom Kiri: Informasi Detail -->
    <div class="lg:col-span-2 space-y-8">
        <div class="admin-card !p-10">
            <div class="flex justify-between items-start mb-10 overflow-hidden">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-soft-grey text-midnight-blue rounded-3xl flex items-center justify-center border border-platinum shadow-sm">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-midnight-blue uppercase tracking-tighter">Kronologi Laporan</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Dikirim oleh <strong>{{ $item->nama_pelapor }}</strong> pada {{ $item->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
                
                <form action="{{ route('admin.pengaduan.status', $item->id) }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <select name="status" onchange="this.form.submit()" class="bg-soft-grey border border-platinum px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest text-midnight-blue focus:outline-none focus:ring-4 focus:ring-gold-dignity/5 outline-none transition-all cursor-pointer">
                        <option value="Masuk" {{ $item->status == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="Diproses" {{ $item->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>
            </div>

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-soft-grey/30 rounded-[2rem] border border-platinum">
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Pelapor Asal</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-midnight-blue text-white rounded-xl flex items-center justify-center font-black text-xs uppercase">{{ substr($item->nama_pelapor, 0, 1) }}</div>
                            <span class="text-sm font-black text-midnight-blue leading-tight">{{ $item->nama_pelapor }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">WhatsApp / Telepon</label>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->kontak_pelapor) }}" target="_blank" class="flex items-center gap-3 text-emerald-600 hover:text-emerald-700 transition-colors">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span class="text-sm font-black">{{ $item->kontak_pelapor }}</span>
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Konten Pengaduan</label>
                    <div class="bg-white p-8 rounded-[2rem] text-sm text-slate-600 leading-relaxed border border-platinum shadow-inner italic">
                        "{{ $item->isi_pengaduan }}"
                    </div>
                </div>

                @if($item->foto_bukti)
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Bukti Pendukung</label>
                    <div class="mt-4 relative group">
                        <img src="{{ asset($item->foto_bukti) }}" class="rounded-[2rem] max-h-96 w-full object-cover shadow-2xl border-4 border-white transition-transform group-hover:scale-[1.01]" alt="Bukti Pengaduan">
                        <div class="absolute inset-0 bg-midnight-blue/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-[2rem] flex items-center justify-center">
                            <a href="{{ asset($item->foto_bukti) }}" target="_blank" class="bg-white text-midnight-blue p-4 rounded-full shadow-xl">
                                <i data-lucide="maximize-2" class="w-6 h-6"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Chat / Balasan -->
        <h3 class="text-xs font-black text-midnight-blue uppercase mb-4 tracking-widest px-4">Percakapan Laporan</h3>
        <div class="admin-card !p-0 overflow-hidden">
            <div class="p-10 space-y-6 max-h-[600px] overflow-y-auto custom-scrollbar bg-slate-50/50">
                @forelse($item->balasan as $msg)
                <div class="flex {{ $msg->pengirim == 'admin' ? 'justify-end' : 'justify-start' }} reveal-on-scroll">
                    <div class="max-w-[85%]">
                        <div class="relative {{ $msg->pengirim == 'admin' ? 'bg-midnight-blue text-white rounded-[1.5rem] rounded-tr-none' : 'bg-white text-slate-700 rounded-[1.5rem] rounded-tl-none border border-platinum shadow-sm' }} p-6">
                            <p class="text-sm leading-relaxed">{{ $msg->isi_balasan }}</p>
                        </div>
                        <div class="mt-2 flex items-center gap-2 {{ $msg->pengirim == 'admin' ? 'justify-end' : 'justify-start' }}">
                            <span class="text-[8px] font-black tracking-widest text-slate-400 uppercase">{{ $msg->created_at->diffForHumans() }}</span>
                            @if($msg->pengirim == 'admin')
                                <i data-lucide="check-check" class="w-3 h-3 text-emerald-500"></i>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 flex flex-col items-center gap-4">
                    <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                        <i data-lucide="message-square" class="w-8 h-8"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Belum ada tanggapan</p>
                </div>
                @endforelse
            </div>

            <div class="p-8 bg-white border-t border-platinum">
                <form action="{{ route('admin.pengaduan.reply', $item->id) }}" method="POST">
                    @csrf
                    <div class="bg-soft-grey rounded-3xl p-2 flex gap-3 items-center border border-platinum focus-within:border-gold-dignity transition-all">
                        <textarea name="isi_balasan" rows="1" required class="flex-1 bg-transparent border-none p-4 text-sm font-medium focus:outline-none resize-none" placeholder="Ketik balasan untuk pelapor..."></textarea>
                        <button type="submit" class="w-12 h-12 bg-midnight-blue text-white rounded-2xl flex items-center justify-center hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-lg hover:scale-105">
                            <i data-lucide="send" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Quick Actions -->
    <div class="space-y-8">
        <div class="admin-card !p-8 bg-midnight-blue text-white shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i data-lucide="shield-check" class="w-24 h-24"></i>
            </div>
            <h4 class="text-xs font-black text-gold-dignity uppercase tracking-widest mb-6 relative z-10">Panduan Admin</h4>
            <div class="space-y-4 relative z-10 text-[11px] text-white/70 leading-relaxed font-bold">
                <div class="flex gap-3">
                    <div class="w-5 h-5 bg-white/10 rounded flex-shrink-0 flex items-center justify-center text-gold-dignity">1</div>
                    <p>Verifikasi bukti yang dilampirkan pelapor untuk memastikan kebenaran informasi.</p>
                </div>
                <div class="flex gap-3">
                    <div class="w-5 h-5 bg-white/10 rounded flex-shrink-0 flex items-center justify-center text-gold-dignity">2</div>
                    <p>Berikan balasan secara sopan dan informatif untuk menjaga marwah institusi.</p>
                </div>
                <div class="flex gap-3">
                    <div class="w-5 h-5 bg-white/10 rounded flex-shrink-0 flex items-center justify-center text-gold-dignity">3</div>
                    <p>Ubah status menjadi 'Selesai' jika masalah sudah tuntas atau informasi sudah jelas.</p>
                </div>
            </div>
        </div>

        <div class="admin-card p-10 text-center border-emerald-500/20 bg-emerald-50/5">
            <div class="w-20 h-20 bg-emerald-500/10 text-emerald-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-emerald-500/10">
                <i data-lucide="check-circle" class="w-10 h-10"></i>
            </div>
            <h4 class="text-sm font-black text-midnight-blue uppercase mb-4 tracking-tighter">Sudah Tuntas?</h4>
            <p class="text-[11px] text-slate-500 mb-8 leading-relaxed font-bold uppercase tracking-tight">Jika laporan telah selesai ditangani, klik tombol di bawah untuk mengarsipkan.</p>
            
            <form action="{{ route('admin.pengaduan.status', $item->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Selesai">
                <button type="submit" class="w-full bg-emerald-500 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-emerald-600 transition-all shadow-lg hover:shadow-emerald-500/20 transform hover:-translate-y-1">
                    Tandai Selesai & Tutup
                </button>
            </form>
        </div>

        <div class="admin-card p-8 border-dashed border-2 border-platinum bg-soft-grey/30">
            <h4 class="text-[10px] font-black text-slate-400 uppercase mb-4 tracking-widest text-center">Informasi Sistem</h4>
            <div class="flex justify-between items-center py-2 border-b border-platinum/50">
                <span class="text-[9px] font-bold text-slate-500 uppercase">Input By</span>
                <span class="text-[10px] font-black text-midnight-blue">PUBLIC USER</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-platinum/50">
                <span class="text-[9px] font-bold text-slate-500 uppercase">Device IP</span>
                <span class="text-[10px] font-black text-midnight-blue">127.0.0.1</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-[9px] font-bold text-slate-500 uppercase">Last Entry</span>
                <span class="text-[10px] font-black text-midnight-blue">{{ $item->updated_at->format('d/m/y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
