@extends('layouts.admin')

@section('title', 'Backup Database')
@section('page_title', 'Manajemen Backup Database')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-lg flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Actions -->
    <div class="admin-card p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-black text-midnight-blue uppercase mb-2">Backup & Restore Database</h3>
                <p class="text-xs text-slate-500">Kelola backup database untuk keamanan data sistem</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="confirmBackup()" class="bg-midnight-blue text-white px-6 py-3 rounded font-bold text-xs uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all duration-300 shadow-lg flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4"></i>
                    Buat Backup Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Warning Notice -->
    <div class="bg-amber-50 border border-amber-200 p-6 rounded-lg">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="shield-alert" class="w-5 h-5 text-white"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-black text-amber-900 uppercase mb-2">Peringatan Penting</h4>
                <ul class="text-xs text-amber-800 space-y-1 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full mt-1.5 flex-shrink-0"></span>
                        <span><strong>Backup Otomatis:</strong> Disarankan membuat backup setiap hari atau sebelum update sistem</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full mt-1.5 flex-shrink-0"></span>
                        <span><strong>Restore Database:</strong> Akan menimpa semua data saat ini dengan data backup</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full mt-1.5 flex-shrink-0"></span>
                        <span><strong>Simpan File:</strong> Download dan simpan backup penting di lokasi aman (eksternal)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Backup Files List -->
    <div class="admin-card">
        <div class="p-6 border-b border-platinum">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-soft-grey flex items-center justify-center text-midnight-blue">
                    <i data-lucide="archive" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-black text-midnight-blue uppercase">Daftar File Backup</h3>
            </div>
        </div>

        @if(count($backups) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-soft-grey border-b border-platinum">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama File</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Ukuran</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Dibuat</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-platinum">
                    @foreach($backups as $backup)
                    <tr class="hover:bg-soft-grey/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <span class="text-sm font-bold text-midnight-blue">{{ $backup['name'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-600">{{ $backup['size'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-600">{{ $backup['date'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Download -->
                                <a href="{{ route('admin.backup.download', $backup['name']) }}" 
                                   class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors" 
                                   title="Download">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                                
                                <!-- Restore -->
                                <button onclick="confirmRestore('{{ $backup['name'] }}')" 
                                        class="p-2 bg-emerald-500 text-white rounded hover:bg-emerald-600 transition-colors" 
                                        title="Restore Database">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                </button>
                                
                                <!-- Delete -->
                                <button type="button" onclick="confirmDelete('{{ $backup['name'] }}')" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
            </div>
            <h4 class="text-sm font-black text-midnight-blue uppercase mb-2">Belum Ada Backup</h4>
            <p class="text-xs text-slate-500">Klik tombol "Buat Backup Baru" untuk membuat backup pertama</p>
        </div>
        @endif
    </div>
</div>

<!-- Hidden Forms -->
<form id="backupForm" action="{{ route('admin.backup.create') }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="restoreForm" action="{{ route('admin.backup.restore') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="backup_file" id="restoreFileName">
</form>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
// Backup Confirmation
function confirmBackup() {
    Swal.fire({
        title: 'Buat Backup Database?',
        html: 'Proses backup akan membuat salinan lengkap database saat ini.<br><br><strong>Pastikan tidak ada proses penting yang sedang berjalan.</strong>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#002147',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-database mr-2"></i> Ya, Buat Backup',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        customClass: {
            confirmButton: 'font-bold uppercase text-xs tracking-widest',
            cancelButton: 'font-bold uppercase text-xs tracking-widest'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sedang Membuat Backup...',
                html: 'Mohon tunggu, proses backup sedang berlangsung.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById('backupForm').submit();
        }
    });
}

// Restore Confirmation
function confirmRestore(filename) {
    Swal.fire({
        title: '⚠️ Peringatan Kritis!',
        html: `
            <div class="text-left space-y-3">
                <p class="font-bold text-red-600">Restore database akan menimpa SEMUA data saat ini dengan data dari backup:</p>
                <p class="bg-gray-100 p-3 rounded font-mono text-sm">${filename}</p>
                <p class="font-bold">Proses ini TIDAK BISA dibatalkan!</p>
                <p class="text-sm text-gray-600">Pastikan Anda sudah membuat backup terbaru sebelum melanjutkan.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-exclamation-triangle mr-2"></i> Ya, Restore Sekarang',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batalkan',
        customClass: {
            confirmButton: 'font-bold uppercase text-xs tracking-widest',
            cancelButton: 'font-bold uppercase text-xs tracking-widest'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Konfirmasi Terakhir',
                html: `Ketik <strong class="text-red-600">RESTORE</strong> untuk melanjutkan:`,
                input: 'text',
                inputPlaceholder: 'Ketik RESTORE',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check mr-2"></i> Lanjutkan',
                cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
                customClass: {
                    confirmButton: 'font-bold uppercase text-xs tracking-widest',
                    cancelButton: 'font-bold uppercase text-xs tracking-widest'
                },
                inputValidator: (value) => {
                    if (value !== 'RESTORE') {
                        return 'Anda harus mengetik "RESTORE" untuk melanjutkan!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang Restore Database...',
                        html: 'Mohon tunggu, proses restore sedang berlangsung.<br><strong class="text-red-600">JANGAN tutup halaman ini!</strong>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('restoreFileName').value = filename;
                    document.getElementById('restoreForm').submit();
                }
            });
        }
    });
}

// Delete Confirmation
function confirmDelete(filename) {
    Swal.fire({
        title: 'Hapus File Backup?',
        html: `File backup yang akan dihapus:<br><br><strong class="font-mono">${filename}</strong><br><br>File yang sudah dihapus tidak dapat dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i> Ya, Hapus',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        customClass: {
            confirmButton: 'font-bold uppercase text-xs tracking-widest',
            cancelButton: 'font-bold uppercase text-xs tracking-widest'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '{{ route("admin.backup.destroy", "") }}/' + filename;
            form.submit();
        }
    });
}
</script>
@endsection
