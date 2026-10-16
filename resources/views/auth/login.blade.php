<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-50 text-zinc-800 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="max-w-sm w-full bg-white p-6 rounded-xl border border-zinc-200 shadow-sm space-y-5">
        <div>
            <h1 class="text-xl font-bold text-zinc-900">Masuk ke Sistem</h1>
            <p class="text-xs text-zinc-500 mt-1">Silakan masuk menggunakan akun Anda.</p>
        </div>

        @if(session('success'))
            <div class="bg-zinc-900 text-white px-3 py-2 rounded-lg text-xs">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com"
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:border-zinc-900">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:border-zinc-900">
            </div>
            <label class="flex items-center gap-2 text-xs text-zinc-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-zinc-300 text-zinc-900"> Ingat saya
            </label>
            <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white text-sm font-medium py-2.5 rounded-lg transition">
                Masuk
            </button>
        </form>

        <p class="text-xs text-center text-zinc-500 pt-2 border-t border-zinc-100">
            Belum punya akun? <a href="{{ route('register') }}" class="text-zinc-900 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</body>
</html>