<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'midnight-blue': '#002147',
                        'gold-dignity': '#C5A059',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-soft-grey min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded p-8 shadow-sm border border-platinum">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-midnight-blue text-gold-dignity rounded flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shield-check" class="w-8 h-8"></i>
                </div>
                <h1 class="text-xl font-black text-midnight-blue uppercase">Admin Login</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Management Portal v2.0</p>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded text-[11px] font-bold mb-6">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full bg-soft-grey border border-platinum pl-11 pr-4 py-2.5 rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none transition-all"
                            placeholder="Masukkan Username">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="password" name="password" required
                            class="w-full bg-soft-grey border border-platinum pl-11 pr-4 py-2.5 rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-midnight-blue text-gold-dignity py-3.5 rounded font-black uppercase text-[11px] tracking-widest shadow-lg shadow-midnight-blue/20 hover:bg-navy-accent transition-all flex items-center justify-center gap-3">
                    Authenticate <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 text-center pt-8 border-t border-platinum">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Lapas Kelas IIB Lamongan</p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
