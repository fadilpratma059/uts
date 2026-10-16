<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Sistem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-50 text-zinc-800 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="max-w-sm w-full bg-white p-6 rounded-xl border border-zinc-200 shadow-sm space-y-5">
        <div>
            <h1 class="text-xl font-bold text-zinc-900">Buat Akun Baru</h1>
            <p class="text-xs text-zinc-500 mt-1">Isi data berikut untuk mengakses dashboard.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg text-xs space-y-1">
               @foreach ($errors->all() as $error)
               <div>• {{ $error }}</div>
               @endforeach
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Anda"
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:border-zinc-900">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:border-zinc-900">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 mb-1">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:border-zinc-900">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:border-zinc-900">
            </div>
            <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white text-sm font-medium py-2.5 rounded-lg transition">
                Daftar & Masuk
            </button>
        </form>

        <p class="text-xs text-center text-zinc-500 pt-2 border-t border-zinc-100">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-zinc-900 font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>
</body>
</html>