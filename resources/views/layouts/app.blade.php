<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ruang Data') - Ruang Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="bg-slate-950 text-white lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <div class="flex items-center gap-3 px-6 py-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-400 text-lg font-black text-slate-950">R</div>
                <div>
                    <p class="font-bold tracking-wide">Ruang Data</p>
                    <p class="text-xs text-slate-400">Manajemen inventaris</p>
                </div>
            </div>

            <nav class="flex gap-2 overflow-x-auto px-4 pb-4 lg:flex-col lg:overflow-visible" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}"
                    class="flex shrink-0 items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-teal-400 text-slate-950' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span aria-hidden="true">▦</span>
                    Dashboard
                </a>
                <a href="{{ route('categories.index') }}"
                    class="flex shrink-0 items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-teal-400 text-slate-950' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span aria-hidden="true">◈</span>
                    Kategori
                </a>
            </nav>

            <div class="mt-auto hidden border-t border-slate-800 p-5 lg:block">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-400">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1 px-4 py-6 sm:px-8 lg:ml-64 lg:px-10 lg:py-9">
            <div class="mx-auto max-w-7xl space-y-6">
                <header class="flex flex-col justify-between gap-2 border-b border-slate-200 pb-5 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-700">Ruang Data</p>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">@yield('page-title', 'Dashboard')</h1>
                        <p class="mt-1 text-sm text-slate-500">@yield('page-description', 'Kelola data dengan lebih mudah.')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">Keluar</button>
                        </form>
                    </div>
                </header>

                @if (session('success'))
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="text-lg leading-none text-emerald-700" aria-label="Tutup notifikasi">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                        <p class="font-semibold">Ada data yang perlu diperiksa:</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
