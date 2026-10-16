@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit kategori')
@section('page-description', 'Perbarui nama dan deskripsi kategori.')

@section('content')
    <div class="mx-auto max-w-3xl">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700">Perbarui data</p>
                <h2 class="mt-1 text-lg font-bold text-slate-950">{{ $category->name }}</h2>
            </div>

            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama kategori</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required maxlength="255"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                </div>
                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="description" name="description" rows="5"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">{{ old('description', $category->description) }}</textarea>
                </div>
                <div>
                    <label for="image" class="mb-1.5 block text-sm font-medium text-slate-700">Foto kategori <span class="font-normal text-slate-400">(opsional, JPG/PNG/WEBP maks. 2 MB)</span></label>
                    @if ($category->image_path)
                        <img src="{{ asset('storage/'.$category->image_path) }}" alt="Foto {{ $category->name }}" class="mb-3 h-32 w-32 rounded-xl object-cover">
                        <p class="mb-2 text-xs text-slate-500">Pilih foto baru untuk mengganti foto saat ini, atau biarkan kosong untuk mempertahankannya.</p>
                    @endif
                    <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                        class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-between">
                    <a href="{{ route('categories.show', $category) }}" class="rounded-xl border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="rounded-xl bg-teal-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-700/20">Simpan perubahan</button>
                </div>
            </form>
        </section>
    </div>
@endsection
