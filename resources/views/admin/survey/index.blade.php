@extends('layouts.admin')

@section('title', 'Kelola Survey Kepuasan')
@section('page_title', 'Indeks Kepuasan Masyarakat')

@section('content')
@php
    $editId = request('edit');
    $editingSurvey = $editId ? $surveys->where('id_survey', $editId)->first() : null;
    $latest = $surveys->where('is_active', 1)->first() ?? $surveys->first();
    $avgIkm = $surveys->avg('skor_ikm');
    
    // Default values if no active survey exists
    $formBulan = $editingSurvey ? $editingSurvey->bulan : ($latest ? $latest->bulan : '');
    $formIpk = $editingSurvey ? $editingSurvey->skor_ipk : ($latest ? $latest->skor_ipk : '');
    $formIkm = $editingSurvey ? $editingSurvey->skor_ikm : ($latest ? $latest->skor_ikm : '');
    $formKet = $editingSurvey ? $editingSurvey->keterangan : ($latest ? $latest->keterangan : 'Baik');
    $formActive = $editingSurvey ? $editingSurvey->is_active : ($latest ? $latest->is_active : 1);
@endphp

<!-- Summary Cards (Premium Dashboard Style) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="admin-card p-6 group hover:border-gold-dignity transition-all reveal-on-scroll">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="database" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded tracking-tighter">Database</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Data</p>
        <p class="text-2xl font-black text-midnight-blue">{{ count($surveys) }}</p>
    </div>
    
    <div class="admin-card p-6 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-emerald-600 rounded flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-white bg-emerald-500 px-2 py-0.5 rounded tracking-tighter">Terakhir</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Skor IKM Aktif</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $latest ? number_format($latest->skor_ikm, 2) : '0.00' }}</p>
    </div>

    <div class="admin-card p-6 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-amber-600 rounded flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-lucide="activity" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-white bg-amber-500 px-2 py-0.5 rounded tracking-tighter">Avg</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata IKM</p>
        <p class="text-2xl font-black text-midnight-blue">{{ number_format($avgIkm, 2) }}</p>
    </div>

    <div class="admin-card p-6 bg-midnight-blue border-midnight-blue group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-white/10 text-gold-dignity rounded flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-bold text-white/50 uppercase tracking-tighter">Status</span>
        </div>
        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1 uppercase">Kinerja Umum</p>
        <p class="text-xl font-black text-white truncate">{{ $latest->keterangan ?? 'BAIK' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- Form Panel (Dashboard Sidebar Style) -->
    <div class="lg:col-span-4 space-y-6 sticky top-6">
        <div class="admin-card p-8 border-t-4 border-t-gold-dignity">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-soft-grey rounded-2xl flex items-center justify-center text-midnight-blue">
                    <i data-lucide="{{ $editingSurvey ? 'edit-3' : 'plus-circle' }}" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase">{{ $editingSurvey ? 'Perbarui Data' : 'Input Data Baru' }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Kelola Indeks Kepuasan</p>
                </div>
            </div>
            
            <form action="{{ $editingSurvey ? route('admin.survey.update', $editingSurvey->id_survey) : route('admin.survey.store') }}" method="POST" class="space-y-5">
                @csrf
                @if($editingSurvey) @method('PUT') @endif
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Bulan & Tahun Periode</label>
                    <div class="relative">
                        <input type="text" name="bulan" value="{{ $formBulan }}" required placeholder="Contoh: JANUARI 2026" class="w-full pl-12 pr-4 py-4 bg-soft-grey border border-platinum rounded-xl focus:ring-4 focus:ring-gold-dignity/5 focus:border-gold-dignity outline-none transition font-bold text-midnight-blue">
                        <i data-lucide="calendar" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Skor IPK</label>
                        <div class="relative">
                            <input type="number" name="skor_ipk" step="0.01" value="{{ $formIpk }}" required placeholder="0.00" class="w-full pl-10 pr-4 py-4 bg-soft-grey border border-platinum rounded-xl focus:border-gold-dignity outline-none transition font-black text-midnight-blue">
                            <i data-lucide="target" class="w-3.5 h-3.5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Skor IKM</label>
                        <div class="relative">
                            <input type="number" name="skor_ikm" step="0.01" value="{{ $formIkm }}" required placeholder="0.00" class="w-full pl-10 pr-4 py-4 bg-soft-grey border border-platinum rounded-xl focus:border-gold-dignity outline-none transition font-black text-midnight-blue text-gold-dignity">
                            <i data-lucide="star" class="w-3.5 h-3.5 absolute left-4 top-1/2 -translate-y-1/2 text-gold-dignity"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Peringkat Kinerja</label>
                    <div class="relative">
                        <select name="keterangan" required class="w-full pl-12 pr-4 py-4 bg-soft-grey border border-platinum rounded-xl focus:border-gold-dignity outline-none transition font-bold text-midnight-blue appearance-none">
                            <option value="Sangat Baik" {{ $formKet == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                            <option value="Baik" {{ $formKet == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Cukup" {{ $formKet == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                            <option value="Kurang" {{ $formKet == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                        </select>
                        <i data-lucide="award" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-soft-grey p-5 rounded-2xl border border-platinum group/active transition-colors hover:border-gold-dignity/30 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ $formActive ? 'checked' : '' }} class="w-5 h-5 rounded text-gold-dignity focus:ring-gold-dignity bg-white border-platinum cursor-pointer">
                    <label for="is_active" class="flex-1 text-[11px] font-black text-midnight-blue uppercase tracking-widest cursor-pointer group-hover/active:text-gold-dignity transition-colors">Tampilkan di Halaman Depan</label>
                </div>

                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full bg-midnight-blue text-white py-5 rounded-xl font-black uppercase tracking-[0.2em] hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-xl shadow-midnight-blue/20 text-[10px] transform hover:-translate-y-1">
                        {{ $editingSurvey ? 'Simpan Perubahan' : 'Publish Survey Baru' }}
                    </button>
                    @if($editingSurvey)
                        <a href="{{ route('admin.survey.index') }}" class="text-center py-2 text-[10px] font-bold text-slate-400 hover:text-red-500 transition-colors uppercase tracking-widest">Batal Edit</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="admin-card p-6 bg-soft-grey border-dashed border-2 border-platinum">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-midnight-blue shadow-sm">
                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                </div>
                <h4 class="text-[11px] font-black text-midnight-blue uppercase">Panduan Skor</h4>
            </div>
            <p class="text-[11px] text-slate-500 leading-relaxed font-bold uppercase tracking-tight">Gunakan skala 1.00 - 4.00 untuk IPK & IKM sesuai dengan pedoman pembangunan ZI Menpan RB.</p>
        </div>
    </div>

    <!-- Main Table View -->
    <div class="lg:col-span-8 space-y-6">
        <div class="admin-card overflow-hidden">
            <div class="px-10 py-6 border-b border-platinum bg-white flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase tracking-tighter">Riwayat Periode Survey</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Menampilkan seluruh data dari database</p>
                </div>
                <span class="px-4 py-2 bg-midnight-blue/5 text-midnight-blue text-[10px] font-black rounded-lg border border-midnight-blue/10">{{ count($surveys) }} TOTAL DATA</span>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-10">Periode</th>
                            <th class="text-center">Skor IPK</th>
                            <th class="text-center">Skor IKM</th>
                            <th class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-platinum">
                        @foreach($surveys as $s)
                        <tr class="hover:bg-soft-grey/30 transition-all group {{ $s->is_active ? 'bg-emerald-50/20' : '' }}">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 {{ $s->is_active ? 'bg-emerald-500 text-white' : 'bg-soft-grey text-midnight-blue' }} rounded-2xl flex items-center justify-center font-black text-xs uppercase shadow-sm">
                                        {{ substr($s->bulan, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-black text-midnight-blue uppercase group-hover:text-gold-dignity transition-colors">{{ $s->bulan }}</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-black uppercase tracking-widest {{ 
                                                $s->keterangan == 'Sangat Baik' ? 'text-emerald-500' : 
                                                ($s->keterangan == 'Baik' ? 'text-indigo-500' : 'text-amber-500') 
                                            }}">{{ $s->keterangan }}</span>
                                            @if($s->is_active)
                                                <div class="flex items-center gap-1.5">
                                                    <div class="w-1 h-1 bg-emerald-500 rounded-full animate-ping"></div>
                                                    <span class="text-[8px] font-black text-emerald-600 uppercase">Live On Site</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="text-lg font-black text-midnight-blue tabular-nums">{{ $s->skor_ipk }}</span>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Max 4.0</p>
                            </td>
                            <td class="text-center">
                                <span class="text-lg font-black text-gold-dignity tabular-nums">{{ $s->skor_ikm }}</span>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Kepuasan</p>
                            </td>
                            <td class="px-10">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.survey.index', ['edit' => $s->id_survey]) }}" title="Edit Data" class="w-9 h-9 flex items-center justify-center bg-white border border-platinum text-midnight-blue rounded-xl hover:bg-midnight-blue hover:text-white transition-all shadow-sm">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    
                                    @if(!$s->is_active)
                                    <form action="{{ route('admin.survey.activate', $s->id_survey) }}" method="POST">
                                        @csrf
                                        <button title="Aktifkan Sekarang" class="w-9 h-9 flex items-center justify-center bg-white border border-platinum text-emerald-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm group">
                                            <i data-lucide="play" class="w-4 h-4 group-hover:fill-current"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.survey.destroy', $s->id_survey) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Permanen" class="w-9 h-9 flex items-center justify-center bg-white border border-platinum text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm group">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($surveys) == 0)
            <div class="p-20 text-center flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                    <i data-lucide="inbox" class="w-10 h-10"></i>
                </div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Belum ada riwayat survey</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
