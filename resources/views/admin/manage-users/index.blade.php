@extends('layouts.admin')

@section('title', 'Kelola User Layanan')
@section('page_title', 'Database User Integrasi')

@section('content')
<div class="space-y-6">
    <!-- Main Content Card -->
    <div class="admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
            <div>
                <h3 class="text-[13px] font-black text-midnight-blue uppercase">Daftar Keluarga/Penjamin Terdaftar</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Database masyarakat yang mengakses layanan integrasi</p>
            </div>
            
            <form method="GET" action="{{ route('admin.manage-users.index') }}" class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama/NIK/Email..." class="pl-8 pr-3 py-1.5 text-[11px] border border-platinum rounded focus:ring-1 focus:ring-midnight-blue w-48 sm:w-64 transition-all bg-soft-grey border-none font-bold">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 group-focus-within:text-midnight-blue"></i>
            </form>
        </div>

        <div class="px-0 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Identitas Penjamin</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">NIK & WBP</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Status Akun</th>
                        <th class="px-6 py-4 text-right text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-platinum">
                    @forelse($users as $user)
                    <tr class="hover:bg-soft-grey/30 transition-all group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" class="w-10 h-10 rounded-xl object-cover border border-platinum" alt="Avatar">
                                @else
                                    <div class="w-10 h-10 bg-soft-grey rounded-xl flex items-center justify-center font-black text-xs text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm font-black text-midnight-blue uppercase group-hover:text-gold-dignity transition-colors">{{ $user->name }}</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <i data-lucide="mail" class="w-2.5 h-2.5 text-slate-400"></i>
                                        <span class="text-[10px] font-bold text-slate-400 lowercase tracking-normal">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-midnight-blue uppercase bg-white px-1.5 py-0.5 rounded border border-platinum shadow-sm">NIK: {{ $user->nik ?? 'Belum Diisi' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 px-2 py-0.5 bg-slate-50 border border-slate-100 rounded-md w-fit">
                                    <i data-lucide="user-minus" class="w-3 h-3 text-slate-400"></i>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase italic">WBP: {{ $user->nama_wbp ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($user->google_id)
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 text-blue-600 border border-blue-100">
                                    <i data-lucide="chrome" class="w-3 h-3"></i>
                                    <span class="text-[9px] font-black uppercase tracking-wider">Google OAuth</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-50 text-slate-600 border border-slate-100">
                                    <i data-lucide="key" class="w-3 h-3"></i>
                                    <span class="text-[9px] font-black uppercase tracking-wider">Manual NIK</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.manage-users.destroy', $user->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-red-400 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm" title="Hapus User">
                                        <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                                    <i data-lucide="users" class="w-8 h-8"></i>
                                </div>
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Tidak ada data user terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 bg-soft-grey/30 border-t border-platinum">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
