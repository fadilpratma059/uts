@extends('layouts.app')

@section('title', 'Kategori')
@section('page-title', 'Kategori')
@section('page-description', 'Kelompokkan dan kelola semua kategori data Anda.')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="font-semibold text-slate-950">Semua kategori</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $categories->count() }} kategori terdaftar</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-800">Daftar kategori</span>
            </div>

            @if ($categories->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500">◈</div>
                    <h3 class="mt-4 font-semibold text-slate-900">Belum ada kategori</h3>
                    <p class="mt-1 text-sm text-slate-500">Tambahkan kategori pertama melalui formulir di samping.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($categories as $category)
                        <article class="flex flex-col gap-4 px-5 py-5 transition hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div class="flex min-w-0 items-start gap-4">
                                @if ($category->image_path)
                                    <img src="{{ asset('storage/'.$category->image_path) }}" alt="Foto {{ $category->name }}" class="h-14 w-14 shrink-0 rounded-xl object-cover">
                                @else
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-teal-50 font-bold text-teal-800">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('categories.show', $category) }}" class="font-semibold text-slate-900 hover:text-teal-700">{{ $category->name }}</a>
                                    <p class="mt-1 line-clamp-2 text-sm text-slate-500">{{ $category->description ?: 'Tidak ada deskripsi.' }}</p>
                                    <p class="mt-2 text-xs font-medium text-slate-400">{{ $category->items_count }} item</p>
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('categories.show', $category) }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-950">Detail</a>
                                <a href="{{ route('categories.edit', $category) }}" class="rounded-lg bg-slate-950 px-3 py-2 text-sm font-semibold text-white transition hover:bg-teal-800">Edit</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700">Kategori baru</p>
                <h2 class="mt-1 text-lg font-bold text-slate-950">Tambah kategori</h2>
                <p class="mt-1 text-sm text-slate-500">Isi nama dan deskripsi kategori.</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama kategori</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                        placeholder="Contoh: Elektronik"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                </div>
                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="description" name="description" rows="4" placeholder="Jelaskan isi kategori ini..."
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="image" class="mb-1.5 block text-sm font-medium text-slate-700">Foto kategori <span class="font-normal text-slate-400">(opsional, JPG/PNG/WEBP maks. 2 MB)</span></label>
                    <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                        class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <button type="submit" class="w-full rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-700/20">Simpan kategori</button>
            </form>
        </section>
    </div>
@endsection
