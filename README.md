Okeee, siap 😭 Aku kasih langsung di chat, lengkap dari awal sampai selesai, tanpa file unduhan dan tanpa contoh tema tertentu. Ini template kosong untuk latihan UTS Laravel: database manual SQL DDL, login/logout, CRUD dua tabel, dan relasi One-to-Many.

Cara pakai: ganti semua nama seperti `nama_tabel_induk`, `nama_tabel_anak`, dan `nama_kolom` sesuai soal UTS kamu. Nama-namanya harus konsisten di semua file.

# A. Persiapan project Laravel

## 1. Buat project baru

Buka terminal Laragon atau VS Code:

```
cd C:\laragon\www
composer create-project laravel/laravel nama_project
cd nama_project
php artisan key:generate
```

Jalankan server nanti setelah semua file selesai dibuat:

```
php artisan serve
```

## 2. Buat database di phpMyAdmin

1. Jalankan MySQL dari Laragon atau XAMPP.
2. Buka `http://localhost/phpmyadmin`.
3. Klik New, lalu buat database.
4. Pilih database tersebut dan buka tab SQL.

Atur koneksi pada file `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Ganti `nama_database` sesuai database yang kamu buat. Kalau MySQL menggunakan password, isi `DB_PASSWORD`.

Kemudian:

```
php artisan config:clear
```

# B. Membuat tabel manual dengan SQL DDL

## 3. Buat tabel users untuk login

Jalankan SQL berikut di phpMyAdmin. Jika tabel `users` sudah ada, periksa strukturnya dan jangan membuat tabel duplikat.

```
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

## 4. Buat dua tabel untuk relasi One-to-Many

SQL berikut merupakan pola yang harus kamu sesuaikan. Ganti nama tabel dan kolom dengan nama yang valid sesuai soal. Jangan menuliskan tanda `< >` ke dalam SQL.

```
CREATE TABLE nama_tabel_induk (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_kolom_induk VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE nama_tabel_anak (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_tabel_induk_id BIGINT UNSIGNED NOT NULL,
    nama_kolom_anak VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (nama_tabel_induk_id)
        REFERENCES nama_tabel_induk(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
```

Penjelasan:

- `PRIMARY KEY`: identitas unik setiap data.
- `FOREIGN KEY`: penghubung antara dua tabel.
- `nama_tabel_induk_id`: kolom relasi yang disimpan pada tabel anak.
- `ON DELETE RESTRICT`: data induk tidak bisa dihapus jika masih memiliki data anak.

Kalau soal memiliki kolom tambahan, tambahkan kolom tersebut ke SQL.

# C. Membuat model dan controller

## 5. Buat model dan controller

Jalankan perintah berikut:

```
php artisan make:model NamaModelInduk
php artisan make:model NamaModelAnak

php artisan make:controller NamaControllerInduk
php artisan make:controller NamaControllerAnak
php artisan make:controller AuthController
```

Nama model dan controller harus sama dengan nama class yang dipakai nanti.

## 6. Model induk

File: `app/Models/NamaModelInduk.php`

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NamaModelInduk extends Model
{
    protected $table = 'nama_tabel_induk';

    protected $fillable = [
        'nama_kolom_induk',
    ];

    public function anak(): HasMany
    {
        return $this->hasMany(
            NamaModelAnak::class,
            'nama_tabel_induk_id'
        );
    }
}
```

`protected $table` menentukan nama tabel database. `fillable` menentukan kolom yang boleh diisi melalui `create()` atau `update()`.

## 7. Model anak

File: `app/Models/NamaModelAnak.php`

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NamaModelAnak extends Model
{
    protected $table = 'nama_tabel_anak';

    protected $fillable = [
        'nama_tabel_induk_id',
        'nama_kolom_anak',
    ];

    public function induk(): BelongsTo
    {
        return $this->belongsTo(
            NamaModelInduk::class,
            'nama_tabel_induk_id'
        );
    }
}
```

- `hasMany()` digunakan pada model induk.
- `belongsTo()` digunakan pada model anak.

## 8. Model User

File: `app/Models/User.php`

Pastikan model User menggunakan autentikasi Laravel. Jika perlu, gunakan struktur berikut:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
```

# D. Membuat controller CRUD

Kalau soal meminta CRUD untuk kedua tabel, keduanya perlu controller masing-masing.

## 9. Controller tabel induk

File: `app/Http/Controllers/NamaControllerInduk.php`

```
<?php

namespace App\Http\Controllers;

use App\Models\NamaModelInduk;
use Illuminate\Http\Request;

class NamaControllerInduk extends Controller
{
    public function index()
    {
        $data = NamaModelInduk::withCount('anak')->get();

        return view('induk.index', compact('data'));
    }

    public function create()
    {
        return view('induk.create');
    }

    public function store(Request $request)
    {
        $validasi = $request->validate([
            'nama_kolom_induk' => 'required|string|max:100',
        ]);

        NamaModelInduk::create($validasi);

        return redirect()->route('induk.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = NamaModelInduk::with('anak')->findOrFail($id);

        return view('induk.show', compact('item'));
    }

    public function edit($id)
    {
        $item = NamaModelInduk::findOrFail($id);

        return view('induk.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validasi = $request->validate([
            'nama_kolom_induk' => 'required|string|max:100',
        ]);

        $item = NamaModelInduk::findOrFail($id);
        $item->update($validasi);

        return redirect()->route('induk.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = NamaModelInduk::findOrFail($id);

        if ($item->anak()->exists()) {
            return back()->with(
                'error',
                'Data masih memiliki data anak dan tidak bisa dihapus.'
            );
        }

        $item->delete();

        return redirect()->route('induk.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
```

## 10. Controller tabel anak

File: `app/Http/Controllers/NamaControllerAnak.php`

```
<?php

namespace App\Http\Controllers;

use App\Models\NamaModelAnak;
use App\Models\NamaModelInduk;
use Illuminate\Http\Request;

class NamaControllerAnak extends Controller
{
    public function index()
    {
        $data = NamaModelAnak::with('induk')->get();

        return view('anak.index', compact('data'));
    }

    public function create()
    {
        $induk = NamaModelInduk::all();

        return view('anak.create', compact('induk'));
    }

    public function store(Request $request)
    {
        $validasi = $request->validate([
            'nama_tabel_induk_id' => 'required|exists:nama_tabel_induk,id',
            'nama_kolom_anak' => 'required|string|max:100',
        ]);

        NamaModelAnak::create($validasi);

        return redirect()->route('anak.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = NamaModelAnak::with('induk')->findOrFail($id);

        return view('anak.show', compact('item'));
    }

    public function edit($id)
    {
        $item = NamaModelAnak::findOrFail($id);
        $induk = NamaModelInduk::all();

        return view('anak.edit', compact('item', 'induk'));
    }

    public function update(Request $request, $id)
    {
        $validasi = $request->validate([
            'nama_tabel_induk_id' => 'required|exists:nama_tabel_induk,id',
            'nama_kolom_anak' => 'required|string|max:100',
        ]);

        $item = NamaModelAnak::findOrFail($id);
        $item->update($validasi);

        return redirect()->route('anak.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = NamaModelAnak::findOrFail($id);
        $item->delete();

        return redirect()->route('anak.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
```

Penting: nama `nama_tabel_induk_id` dan `nama_kolom_anak` harus sama dengan kolom di database, model, dan form. Kalau tabel memiliki kolom lain, masukkan juga ke `$fillable`, validasi, dan form.

# E. Login dan logout

## 11. Isi AuthController

File: `app/Http/Controllers/AuthController.php`

```
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('induk.index');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('induk.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

## 12. Atur route

Ganti isi `routes/web.php` menjadi:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NamaControllerInduk;
use App\Http\Controllers\NamaControllerAnak;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::resource('induk', NamaControllerInduk::class);
    Route::resource('anak', NamaControllerAnak::class);
});
```

`Route::resource()` membuat route CRUD standar untuk masing-masing controller. Route `auth` melindungi halaman CRUD agar hanya bisa dibuka setelah login.

# F. Membuat seluruh view

## 13. Buat struktur folder

Buat folder dan file berikut di `resources/views`:

```
resources/views/
├── layouts/
│   └── app.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── induk/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
└── anak/
    ├── index.blade.php
    ├── create.blade.php
    ├── show.blade.php
    └── edit.blade.php
```

## 14. Layout utama

File: `resources/views/layouts/app.blade.php`

```
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Aplikasi')</title>
</head>
<body>
    <nav>
        <a href="{{ route('induk.index') }}">Data Induk</a>
        <a href="{{ route('anak.index') }}">Data Anak</a>

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
```

## 15. View login

File: `resources/views/auth/login.blade.php`

```
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <a href="{{ route('register') }}">Buat akun</a>
</body>
</html>
```

## 16. View register

File: `resources/views/auth/register.blade.php`

```
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Register</title>
</head>
<body>
    <h1>Daftar Akun</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register.process') }}">
        @csrf

        <label>Nama</label>
        <input name="name" value="{{ old('name') }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password minimal 8 karakter</label>
        <input type="password" name="password" required>

        <label>Konfirmasi password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
```

## 17. View index induk

File: `resources/views/induk/index.blade.php`

```
@extends('layouts.app')

@section('title', 'Data Induk')

@section('content')
<h1>Data Induk</h1>

<a href="{{ route('induk.create') }}">Tambah Data</a>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jumlah Anak</th>
        <th>Aksi</th>
    </tr>

    @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_kolom_induk }}</td>
            <td>{{ $item->anak_count }}</td>
            <td>
                <a href="{{ route('induk.show', $item->id) }}">Detail</a>
                <a href="{{ route('induk.edit', $item->id) }}">Edit</a>

                <form method="POST"
                      action="{{ route('induk.destroy', $item->id) }}"
                      style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">Belum ada data.</td>
        </tr>
    @endforelse
</table>
@endsection
```

## 18. View create induk

File: `resources/views/induk/create.blade.php`

```
@extends('layouts.app')

@section('title', 'Tambah Data Induk')

@section('content')
<h1>Tambah Data Induk</h1>

<form method="POST" action="{{ route('induk.store') }}">
    @csrf

    <label>Nama</label>
    <input name="nama_kolom_induk"
           value="{{ old('nama_kolom_induk') }}" required>

    <button type="submit">Simpan</button>
</form>
@endsection
```

## 19. View edit induk

File: `resources/views/induk/edit.blade.php`

```
@extends('layouts.app')

@section('title', 'Edit Data Induk')

@section('content')
<h1>Edit Data Induk</h1>

<form method="POST" action="{{ route('induk.update', $item->id) }}">
    @csrf
    @method('PUT')

    <label>Nama</label>
    <input name="nama_kolom_induk"
           value="{{ old('nama_kolom_induk', $item->nama_kolom_induk) }}"
           required>

    <button type="submit">Perbarui</button>
</form>
@endsection
```

## 20. View detail induk

File: `resources/views/induk/show.blade.php`

```
@extends('layouts.app')

@section('title', 'Detail Data Induk')

@section('content')
<h1>Detail Data Induk</h1>

<p>Nama: {{ $item->nama_kolom_induk }}</p>

<h2>Data Anak</h2>
<ul>
    @forelse($item->anak as $anak)
        <li>{{ $anak->nama_kolom_anak }}</li>
    @empty
        <li>Belum ada data anak.</li>
    @endforelse
</ul>

<a href="{{ route('induk.index') }}">Kembali</a>
@endsection
```

## 21. View index anak

File: `resources/views/anak/index.blade.php`

```
@extends('layouts.app')

@section('title', 'Data Anak')

@section('content')
<h1>Data Anak</h1>

<a href="{{ route('anak.create') }}">Tambah Data</a>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama Anak</th>
        <th>Nama Induk</th>
        <th>Aksi</th>
    </tr>

    @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_kolom_anak }}</td>
            <td>{{ $item->induk->nama_kolom_induk }}</td>
            <td>
                <a href="{{ route('anak.show', $item->id) }}">Detail</a>
                <a href="{{ route('anak.edit', $item->id) }}">Edit</a>

                <form method="POST"
                      action="{{ route('anak.destroy', $item->id) }}"
                      style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">Belum ada data.</td>
        </tr>
    @endforelse
</table>
@endsection
```

## 22. View create anak

File: `resources/views/anak/create.blade.php`

```
@extends('layouts.app')

@section('title', 'Tambah Data Anak')

@section('content')
<h1>Tambah Data Anak</h1>

@if($induk->isEmpty())
    <p>Tambahkan data induk terlebih dahulu.</p>
    <a href="{{ route('induk.create') }}">Tambah Data Induk</a>
@else
    <form method="POST" action="{{ route('anak.store') }}">
        @csrf

        <label>Pilih Induk</label>
        <select name="nama_tabel_induk_id" required>
            <option value="">-- Pilih --</option>
            @foreach($induk as $parent)
                <option value="{{ $parent->id }}"
                    @selected(old('nama_tabel_induk_id') == $parent->id)>
                    {{ $parent->nama_kolom_induk }}
                </option>
            @endforeach
        </select>

        <label>Nama Anak</label>
        <input name="nama_kolom_anak"
               value="{{ old('nama_kolom_anak') }}" required>

        <button type="submit">Simpan</button>
    </form>
@endif
@endsection
```

## 23. View edit anak

File: `resources/views/anak/edit.blade.php`

```
@extends('layouts.app')

@section('title', 'Edit Data Anak')

@section('content')
<h1>Edit Data Anak</h1>

<form method="POST" action="{{ route('anak.update', $item->id) }}">
    @csrf
    @method('PUT')

    <label>Pilih Induk</label>
    <select name="nama_tabel_induk_id" required>
        @foreach($induk as $parent)
            <option value="{{ $parent->id }}"
                @selected(old('nama_tabel_induk_id', $item->nama_tabel_induk_id) == $parent->id)>
                {{ $parent->nama_kolom_induk }}
            </option>
        @endforeach
    </select>

    <label>Nama Anak</label>
    <input name="nama_kolom_anak"
           value="{{ old('nama_kolom_anak', $item->nama_kolom_anak) }}"
           required>

    <button type="submit">Perbarui</button>
</form>
@endsection
```

## 24. View detail anak

File: `resources/views/anak/show.blade.php`

```
@extends('layouts.app')

@section('title', 'Detail Data Anak')

@section('content')
<h1>Detail Data Anak</h1>

<p>Nama: {{ $item->nama_kolom_anak }}</p>
<p>Induk: {{ $item->induk->nama_kolom_induk }}</p>

<a href="{{ route('anak.index') }}">Kembali</a>
@endsection
```

# G. Menjalankan dan menguji

## 25. Periksa route

```
php artisan config:clear
php artisan route:list
```

Pastikan route login, register, logout, induk, dan anak muncul.

## 26. Jalankan aplikasi

```
php artisan serve
```

Buka alamat yang ditampilkan di terminal, biasanya `http://127.0.0.1:8000`.

## 27. Urutan pengujian

1. Buka halaman `/register`.
2. Buat akun dan login.
3. Tambah data induk.
4. Buka menu data anak dan tambahkan data anak dengan memilih induk.
5. Coba fitur detail, edit, dan hapus pada kedua tabel.
6. Pastikan nama induk tampil pada daftar anak.
7. Coba menghapus data induk yang masih memiliki anak.
8. Logout dan pastikan halaman CRUD meminta login kembali.

# H. Error umum

- Unknown database: nama database di `.env` salah atau belum dibuat.
- Table doesn't exist: SQL belum dijalankan atau nama tabel berbeda.
- Unknown column: nama kolom di SQL, model, controller, dan Blade belum sama.
- MassAssignmentException: tambahkan kolom yang disimpan ke `$fillable`.
- View not found: pastikan file Blade berada di folder yang benar.
- Route not defined: cek `routes/web.php` dan jalankan `php artisan route:list`.
- Target class does not exist: periksa nama file, namespace, class, dan import controller.
- Foreign key error: pastikan tipe foreign key cocok dengan primary key dan data induk sudah ada.
- Login gagal: pastikan password tersimpan dalam bentuk hash. Kode register di atas memakai `Hash::make()`.
- 419 Page Expired: pastikan form POST memiliki `@csrf`.
- Method not allowed: form edit memakai `@method('PUT')`; form hapus memakai `@method('DELETE')`.

# I. Ringkasan fungsi untuk UTS

| Fungsi                  | Kegunaan                                         |
| ----------------------- | ------------------------------------------------ |
| `index()`               | Menampilkan semua data                           |
| `create()`              | Menampilkan form tambah                          |
| `store()`               | Validasi dan menyimpan data                      |
| `show($id)`             | Menampilkan detail                               |
| `edit($id)`             | Menampilkan form edit                            |
| `update($request, $id)` | Memperbarui data                                 |
| `destroy($id)`          | Menghapus data                                   |
| `hasMany()`             | Satu induk memiliki banyak anak                  |
| `belongsTo()`           | Anak terhubung ke satu induk                     |
| `Route::resource()`     | Membuat route CRUD standar                       |
| `Auth::attempt()`       | Memeriksa login                                  |
| `Auth::logout()`        | Mengakhiri login                                 |
| `Hash::make()`          | Meng-hash password                               |
| `@csrf`                 | Token keamanan form                              |
| `@method('PUT')`        | Menandai form sebagai update                     |
| `@method('DELETE')`     | Menandai form sebagai hapus                      |
| `$fillable`             | Menentukan kolom yang bisa diisi mass assignment |
| `findOrFail($id)`       | Mencari data atau mengembalikan 404              |
| `$request->validate()`  | Memvalidasi input                                |

## Checklist akhir

- Database dan tabel sudah dibuat manual.
- `.env` mengarah ke database yang benar.
- Nama tabel dan kolom konsisten di semua file.
- Model memiliki `$table`, `$fillable`, dan relasi.
- Kedua controller CRUD lengkap.
- Login dan logout tersedia.
- Semua route terdaftar.
- Semua file Blade yang dipanggil sudah dibuat.
- Data anak bisa memilih data induk.
- Semua fitur diuji melalui browser.

Ingat: kode di atas adalah template generik. Kamu harus mengganti placeholder sesuai soal, termasuk jika setiap tabel memiliki lebih dari satu kolom. Kalau hanya satu tabel yang diminta CRUD, controller tabel kedua bisa disesuaikan dengan kebutuhan soal.