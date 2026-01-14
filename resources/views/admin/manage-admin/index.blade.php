@extends('layouts.admin')

@section('title', 'Kelola Admin')
@section('page_title', 'Manajemen Staff & Admin')

@section('header_actions')
<a href="{{ route('admin.manage-admin.create') }}" class="btn-compact bg-midnight-blue text-white hover:bg-gold-dignity transition-all shadow-lg shadow-midnight-blue/20 group">
    <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i>
    <span>Tambah Admin Baru</span>
</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Main Content Card -->
    <div class="admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
            <div>
                <h3 class="text-[13px] font-black text-midnight-blue uppercase">Daftar Akun Administrator</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kelola hak akses dan akun staff operasional</p>
            </div>
        </div>

        <div class="px-0 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Username</th>
                        <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Role / Jabatan</th>
                        <th class="px-6 py-4 text-right text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-platinum">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-platinum">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-soft-grey/30 transition-all group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-soft-grey rounded-xl flex items-center justify-center font-black text-xs text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all">
                                    {{ substr($admin->nama, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-sm font-black text-midnight-blue uppercase group-hover:text-gold-dignity transition-colors">{{ $admin->nama }}</span>
                                    @if($admin->id_admin === auth()->id())
                                        <span class="ml-2 px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[8px] font-black uppercase rounded border border-indigo-100 italic">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <code class="text-[11px] font-bold text-slate-500 bg-slate-50 px-2 py-1 rounded border border-slate-100">{{ $admin->username }}</code>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $badge = match($admin->role) {
                                    'Super Admin' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    'Layanan' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Humas' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Pengaduan' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-slate-50 text-slate-500 border-slate-100'
                                };
                            @endphp
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded border {{ $badge }}">
                                <span class="text-[9px] font-black uppercase tracking-wider">{{ $admin->role }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.manage-admin.edit', $admin->id_admin) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-slate-500 rounded-xl hover:bg-midnight-blue hover:text-white hover:border-midnight-blue transition-all shadow-sm" title="Edit Data">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                @if($admin->id_admin !== auth()->id())
                                <form action="{{ route('admin.manage-admin.destroy', $admin->id_admin) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white border border-platinum text-red-400 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm" title="Hapus Akun">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                                    <i data-lucide="shield-alert" class="w-8 h-8"></i>
                                </div>
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Tidak ada data admin ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
