@extends('layouts.admin')

@section('title', 'Respon Layanan')
@section('page_title', 'Respon Terhadap Layanan')

@section('content')
<!-- Summary Cards (Premium Dashboard Style) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded tracking-tighter">Database</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Respon</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats['total'] }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-emerald-600 rounded flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="star" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-white bg-emerald-500 px-2 py-0.5 rounded tracking-tighter">Avg</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata Rating</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats['average'] }}<span class="text-sm text-slate-300 font-bold ml-1">/ 5.0</span></p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-amber-600 rounded flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-white bg-amber-500 px-2 py-0.5 rounded tracking-tighter">Bulan Ini</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Respon Masuk</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats['last_month'] }}</p>
    </div>

    <div class="admin-card p-5 bg-midnight-blue border-midnight-blue group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-white/10 text-gold-dignity rounded flex items-center justify-center">
                <i data-lucide="activity" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-bold text-white/50 uppercase tracking-tighter">Real-time</span>
        </div>
        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1 uppercase">Sistem Penilaian</p>
        <p class="text-lg font-black text-white truncate uppercase">Online</p>
    </div>
</div>

<div class="admin-card overflow-hidden reveal-on-scroll" style="transition-delay: 400ms;">
    <div class="px-6 py-4 border-b border-platinum flex justify-between items-center bg-white">
        <div>
            <h3 class="text-[13px] font-black text-midnight-blue uppercase">Log Penilaian Layanan Masyarakat</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Seluruh respon yang masuk melalui website</p>
        </div>
        <span class="px-3 py-1 bg-midnight-blue/5 text-midnight-blue text-[10px] font-black rounded border border-midnight-blue/10 uppercase tracking-tighter">{{ $ratings->total() }} TOTAL</span>
    </div>
    <div class="p-0 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest">Pelapor & Layanan</th>
                    <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest">Rating</th>
                    <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest">Komentar</th>
                    <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-right text-[11px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-platinum">
                @forelse($ratings as $r)
                <tr class="hover:bg-soft-grey/30 transition-all group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-soft-grey rounded-xl flex items-center justify-center font-black text-xs text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all">
                                {{ substr($r->nama ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <span class="text-sm font-black text-midnight-blue uppercase group-hover:text-gold-dignity transition-colors">{{ $r->nama ?? 'Anonim' }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">{{ $r->jenis_layanan }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <div class="flex justify-center gap-0.5 text-amber-500">
                            @for($i=1; $i<=5; $i++)
                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $r->rating ? 'fill-current' : 'text-slate-200' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-[13px] text-slate-600 italic leading-relaxed line-clamp-2 max-w-sm">"{{ $r->komentar ?? 'Tidak ada komentar tertulis' }}"</p>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="text-[11px] font-black text-midnight-blue/40 uppercase tracking-tighter">{{ $r->created_at->translatedFormat('d M Y') }}</span>
                        <p class="text-[9px] font-bold text-slate-300 uppercase">{{ $r->created_at->format('H:i') }} WIB</p>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex justify-end">
                            <form action="{{ route('admin.rating.destroy', $r->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center bg-white border border-platinum text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm group/btn">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover/btn:scale-110 transition-transform"></i>
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
                                <i data-lucide="inbox" class="w-8 h-8"></i>
                            </div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Belum ada respon layanan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ratings->hasPages())
    <div class="px-6 py-4 bg-soft-grey/30 border-t border-platinum">
        {{ $ratings->links() }}
    </div>
    @endif
</div>
@endsection
