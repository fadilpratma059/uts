@extends('layouts.app')

@section('title', $category->name)
@section('page-title', $category->name)
@section('page-description', 'Detail kategori dan daftar item yang terhubung.')

@section('content')
    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    @if ($category->image_path)
                        <img src="{{ asset('storage/'.$category->image_path) }}" alt="Foto {{ $category->name }}" class="h-20 w-20 shrink-0 rounded-2xl object-cover">
                    @else
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-teal-50 text-xl font-bold text-teal-800">
                            {{ strtoupper(substr($category->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-teal-700">Kategori</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950">{{ $category->name }}</h2>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $category->description ?: 'Kategori ini belum memiliki deskripsi.' }}</p>
                    </div>
                </div>
                <div class="flex shrink-0 gap-2">
                    <a href="{{ route('categories.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Kembali</a>
                    <a href="{{ route('categories.edit', $category) }}" class="rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">Edit kategori</a>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                <h2 class="font-semibold text-slate-950">Item dalam kategori</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $category->items->count() }} item terhubung</p>
            </div>

            @if ($category->items->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="font-medium text-slate-700">Belum ada item di kategori ini.</p>
                    <a href="{{ route('dashboard') }}" class="mt-2 inline-flex text-sm font-semibold text-teal-700 hover:text-teal-900">Kelola item di dashboard <span class="ml-1" aria-hidden="true">→</span></a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Item</th>
                                <th class="px-6 py-3 font-semibold">Jumlah</th>
                                <th class="px-6 py-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($category->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($item->image_path)
                                                <img src="{{ asset('storage/'.$item->image_path) }}" alt="Foto {{ $item->title }}" class="h-11 w-11 shrink-0 rounded-lg object-cover">
                                            @else
                                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-slate-100 font-semibold text-slate-500">{{ strtoupper(substr($item->title, 0, 1)) }}</div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $item->title }}</p>
                                                @if ($item->description)
                                                    <p class="mt-1 max-w-md text-xs text-slate-500">{{ $item->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Item terkait akan menjadi Belum Tersedia.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">Hapus kategori</button>
        </form>
    </div>
@endsection
