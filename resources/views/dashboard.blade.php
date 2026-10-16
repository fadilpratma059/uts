@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan dan pengelolaan item inventaris Anda.')

@section('content')
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-2xl bg-slate-950 px-6 py-7 text-white shadow-sm sm:px-8 sm:py-9">
            <div class="relative z-10 max-w-2xl">
                <p class="text-sm font-semibold text-teal-300">Selamat datang, {{ auth()->user()->name ?? 'User' }}</p>
                <h2 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Semua data penting, dalam satu tempat.</h2>
                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300">Pantau inventaris dan atur kategori dengan cepat dari ruang kerja Anda.</p>
                <a href="#item-baru" class="mt-5 inline-flex items-center rounded-xl bg-teal-400 px-4 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-teal-300">Tambah item <span class="ml-2" aria-hidden="true">↓</span></a>
            </div>
            <div class="pointer-events-none absolute -right-12 -top-24 h-72 w-72 rounded-full border-[36px] border-teal-400/10"></div>
            <div class="pointer-events-none absolute -bottom-32 right-28 h-64 w-64 rounded-full bg-teal-400/10 blur-2xl"></div>
        </section>

        <section class="grid gap-4 sm:grid-cols-3" aria-label="Ringkasan data">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total item</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ $items->count() }}</p>
                <p class="mt-1 text-xs text-slate-400">Item tersimpan</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Item aktif</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-700">{{ $items->where('is_active', true)->count() }}</p>
                <p class="mt-1 text-xs text-slate-400">Siap digunakan</p>
            </article>
            <a href="{{ route('categories.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-teal-300 hover:shadow-md">
                <p class="text-sm font-medium text-slate-500">Kategori</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ $categories->count() }}</p>
                <p class="mt-1 text-xs font-medium text-teal-700">Lihat kategori <span aria-hidden="true">→</span></p>
            </a>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h2 class="font-semibold text-slate-950">Daftar item</h2>
                        <p class="mt-1 text-sm text-slate-500">Semua item yang tersimpan di inventaris.</p>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $items->count() }} item</span>
                </div>

                @if ($items->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500">▦</div>
                        <h3 class="mt-4 font-semibold text-slate-900">Belum ada item</h3>
                        <p class="mt-1 text-sm text-slate-500">Tambahkan item pertama menggunakan formulir di samping.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-5 py-3 font-semibold sm:px-6">Item & detail</th>
                                    <th class="px-5 py-3 font-semibold">Kategori</th>
                                    <th class="px-5 py-3 font-semibold">Jumlah</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                    <th class="px-5 py-3 text-right font-semibold sm:px-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($items as $item)
                                    <tr class="transition hover:bg-slate-50/70">
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-3">
                                                @if ($item->image_path)
                                                    <img src="{{ asset('storage/'.$item->image_path) }}" alt="Foto {{ $item->title }}" class="h-12 w-12 shrink-0 rounded-lg object-cover">
                                                @else
                                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 font-semibold text-slate-500">{{ strtoupper(substr($item->title, 0, 1)) }}</div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-slate-900">{{ $item->title }}</p>
                                                    <p class="mt-1 max-w-xs truncate text-xs text-slate-500">{{ $item->description ?: 'Belum ada detail.' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-slate-600">{{ $item->category?->name ?? 'Belum Tersedia' }}</td>
                                        <td class="px-5 py-4 text-slate-600">{{ $item->quantity }}</td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                                            <button type="button"
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->title }}"
                                                data-category="{{ $item->category_id }}"
                                                data-qty="{{ $item->quantity }}"
                                                data-active="{{ $item->is_active ? '1' : '0' }}"
                                                data-description="{{ $item->description }}"
                                                data-image-url="{{ $item->image_path ? asset('storage/'.$item->image_path) : '' }}"
                                                data-update-url="{{ route('items.update', ['item' => '__item__']) }}"
                                                onclick="openEditItem(this)"
                                                class="rounded-lg px-2 py-1 text-xs font-semibold text-teal-800 hover:bg-teal-50">Edit</button>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg px-2 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <section id="item-baru" class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700">Inventaris</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-950">Tambah item</h2>
                    <p class="mt-1 text-sm text-slate-500">Masukkan detail item yang ingin dicatat.</p>
                </div>
                <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="mb-1.5 block text-sm font-medium text-slate-700">Nama item</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Laptop"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                    </div>
                    <div>
                        <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Detail item <span class="font-normal text-slate-400">(opsional)</span></label>
                        <textarea id="description" name="description" rows="3" placeholder="Tambahkan keterangan item..."
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                        <select id="category_id" name="category_id"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                            <option value="">Belum dikategorikan</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($categories->isEmpty())
                            <a href="{{ route('categories.index') }}" class="mt-2 inline-flex text-xs font-semibold text-teal-700 hover:text-teal-900">Buat kategori terlebih dahulu <span class="ml-1" aria-hidden="true">→</span></a>
                        @endif
                    </div>
                    <div>
                        <label for="quantity" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah</label>
                        <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}" min="0" required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                    </div>
                    <div>
                        <label for="item_image" class="mb-1.5 block text-sm font-medium text-slate-700">Foto item <span class="font-normal text-slate-400">(opsional, maks. 2 MB)</span></label>
                        <input id="item_image" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                            class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl bg-slate-50 p-3">
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                        <span class="text-sm font-medium text-slate-700">Tandai sebagai aktif</span>
                    </label>
                    <button type="submit" class="w-full rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-700/20">Simpan item</button>
                </form>
            </section>
        </div>
    </div>

    <div id="modalEditItem" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700">Perbarui inventaris</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-950">Edit item</h2>
                </div>
                <button type="button" onclick="closeEditItem()" class="rounded-lg px-2 py-1 text-2xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">&times;</button>
            </div>
            <form id="formEditItem" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_item_title" class="mb-1.5 block text-sm font-medium text-slate-700">Nama item</label>
                    <input id="edit_item_title" type="text" name="title" required maxlength="255" class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                </div>
                <div>
                    <label for="edit_item_description" class="mb-1.5 block text-sm font-medium text-slate-700">Detail item <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="edit_item_description" name="description" rows="3" class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10"></textarea>
                </div>
                <div>
                    <label for="edit_item_category" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                    <select id="edit_item_category" name="category_id" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm outline-none focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                        <option value="">Belum dikategorikan</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_item_qty" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah</label>
                    <input id="edit_item_qty" type="number" name="quantity" min="0" required class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm outline-none focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10">
                </div>
                <div>
                    <label for="edit_item_image" class="mb-1.5 block text-sm font-medium text-slate-700">Foto item <span class="font-normal text-slate-400">(opsional, maks. 2 MB)</span></label>
                    <img id="edit_item_image_preview" alt="Foto item saat ini" class="mb-3 hidden h-24 w-24 rounded-xl object-cover">
                    <p class="mb-2 text-xs text-slate-500">Pilih foto baru untuk mengganti foto saat ini, atau biarkan kosong untuk mempertahankannya.</p>
                    <input id="edit_item_image" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                        class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <label class="flex cursor-pointer items-center gap-3 rounded-xl bg-slate-50 p-3">
                    <input id="edit_item_active" type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                    <span class="text-sm font-medium text-slate-700">Item aktif</span>
                </label>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeEditItem()" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openEditItem(button) {
            const modal = document.getElementById('modalEditItem');
            const itemId = button.dataset.id;

            document.getElementById('formEditItem').action = button.dataset.updateUrl.replace('__item__', itemId);
            document.getElementById('edit_item_title').value = button.dataset.title || '';
            document.getElementById('edit_item_description').value = button.dataset.description || '';
            document.getElementById('edit_item_category').value = button.dataset.category || '';
            document.getElementById('edit_item_qty').value = button.dataset.qty || 0;
            document.getElementById('edit_item_active').checked = button.dataset.active === '1';
            const imagePreview = document.getElementById('edit_item_image_preview');
            imagePreview.src = button.dataset.imageUrl || '';
            imagePreview.classList.toggle('hidden', !button.dataset.imageUrl);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditItem() {
            const modal = document.getElementById('modalEditItem');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('modalEditItem').addEventListener('click', (event) => {
            if (event.target.id === 'modalEditItem') {
                closeEditItem();
            }
        });
    </script>
@endpush
