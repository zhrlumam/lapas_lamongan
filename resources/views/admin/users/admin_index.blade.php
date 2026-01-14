@extends('layouts.admin')

@section('title', 'Manajemen Admin')
@section('page_title', 'Kelola Administrator')

@section('header_actions')
<a href="{{ route('admin.management.admins.create') }}" class="btn-compact bg-midnight-blue text-white hover:bg-navy-accent">
    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Admin
</a>
@endsection

@section('content')
<div class="admin-card p-6 reveal-on-scroll">
    <div class="table-container">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td class="font-bold text-midnight-blue">{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $admin->role == 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                               ($admin->role == 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ strtoupper(str_replace('_', ' ', $admin->role)) }}
                        </span>
                    </td>
                    <td class="text-slate-500 text-sm">{{ $admin->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.management.admins.edit', $admin->id) }}" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                               title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            
                            @if(auth()->id() != $admin->id)
                            <form action="{{ route('admin.management.admins.destroy', $admin->id) }}" method="POST" class="delete-form inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $admins->links() }}
    </div>
</div>
@endsection
