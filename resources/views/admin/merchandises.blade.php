<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merch & Quota - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col lg:flex-row selection:bg-[#23A9BD] selection:text-white">

    <!-- Sidebar Kiri Tetap -->
    @include('admin.partials.sidebar')

    <!-- Area Konten Kanan -->
    <div class="flex-1 h-screen h-dvh overflow-y-auto no-scrollbar flex flex-col p-3 sm:p-4 lg:px-6 lg:py-4 bg-white min-w-0">

        @include('admin.partials.navbar')

        <main class="flex-1 bg-[#F8F9FA] border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[3px_3px_0px_#010310] flex flex-col gap-3.5">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="font-nohemi font-bold text-lg sm:text-xl tracking-tight text-[#010310] leading-tight">
                        Merchandise &amp; Production Quota
                    </h2>
                    <p class="text-[11px] text-[#010310]/55 font-medium">
                        Kelola item fanart, harga, kategori fandom, dan batas kuota slot cetak
                    </p>
                </div>
                @if(request('category_id'))
                    <a href="{{ route('admin.merchandises') }}"
                       class="px-3 py-1 rounded-full bg-white border border-[#010310] text-[11px] font-bold hover:bg-[#23A9BD] hover:text-white transition inline-flex items-center gap-1.5">
                        <x-icon name="x-mark" class="w-3 h-3"/> Reset Filter Fandom
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="px-3.5 py-2 rounded-xl border border-[#010310] bg-emerald-100 text-[#010310] text-[11px] font-bold shadow-[2px_2px_0px_#010310] flex items-center gap-1.5">
                    <x-icon name="check" class="w-3 h-3 flex-shrink-0"/> {{ session('success') }}
                </div>
            @endif

            <!-- BENTO GRID: Kiri (Form CRUD Merch + Fandom) | Kanan (Daftar Item) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 flex-1">

                <!-- KOLOM KIRI (4 Kolom): Form Tambah / Edit Merch & Kategori -->
                <div class="lg:col-span-4 flex flex-col gap-3.5">

                    <!-- Bento 1: Form Tambah / Edit Merchandise -->
                    <section class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-nohemi font-bold text-sm text-[#010310] flex items-center gap-1.5">
                                @if($editMerch)
                                    <x-icon name="pencil" class="w-3.5 h-3.5"/> Edit Merchandise
                                @else
                                    <x-icon name="plus" class="w-3.5 h-3.5"/> Tambah Merch Baru
                                @endif
                            </h3>
                            @if($editMerch)
                                <a href="{{ route('admin.merchandises') }}" class="text-[10px] font-bold text-rose-500 hover:underline">Batal</a>
                            @endif
                        </div>

                        <form action="{{ $editMerch ? route('admin.merchandises.update', $editMerch->id) : route('admin.merchandises.store') }}"
                              method="POST" enctype="multipart/form-data" class="space-y-2.5 text-xs">
                            @csrf
                            @if($editMerch) @method('PUT') @endif

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Nama Merchandise</label>
                                <input type="text" name="name" value="{{ old('name', $editMerch->name ?? '') }}" required
                                       placeholder="Contoh: Furina Acrylic Standee"
                                       class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                                @error('name') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Fandom Series</label>
                                    <select name="fandom_category_id" required
                                            class="w-full px-2.5 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" @selected(old('fandom_category_id', $editMerch->fandom_category_id ?? '') == $cat->id)>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Tipe Item</label>
                                    <select name="item_type" required
                                            class="w-full px-2.5 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                                        <option value="acrylic_stand" @selected(old('item_type', $editMerch->item_type ?? '') === 'acrylic_stand')>Acrylic Stand</option>
                                        <option value="keychain" @selected(old('item_type', $editMerch->item_type ?? '') === 'keychain')>Keychain</option>
                                        <option value="art_print" @selected(old('item_type', $editMerch->item_type ?? '') === 'art_print')>Art Print</option>
                                        <option value="photocard" @selected(old('item_type', $editMerch->item_type ?? '') === 'photocard')>Photocard</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Harga (Rp)</label>
                                    <input type="number" name="price" value="{{ old('price', $editMerch->price ?? '') }}" required min="1000"
                                           placeholder="45000"
                                           class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Kuota Slot PO</label>
                                    <input type="number" name="stock_quota" value="{{ old('stock_quota', $editMerch->stock_quota ?? '') }}" required min="0"
                                           placeholder="50"
                                           class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                                </div>
                            </div>

                            <!-- Unggahan gambar produk (opsional) -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Gambar Produk (opsional, maks. 20MB)</label>
                                @if($editMerch?->imageUrl())
                                    <img src="{{ $editMerch->imageUrl() }}" alt="Gambar {{ $editMerch->name }}"
                                         class="h-14 w-full object-cover rounded-xl border border-[#010310]/20 mb-1">
                                @endif
                                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                                       class="w-full text-[10px] font-semibold text-[#010310]/70 file:mr-2 file:px-3 file:py-1 file:rounded-full file:border-[1.5px] file:border-[#010310] file:bg-[#F8F9FA] file:text-[10px] file:font-bold file:cursor-pointer hover:file:bg-[#23A9BD] hover:file:text-white">
                                @error('image') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-2 mt-1 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-[11px] uppercase tracking-wider border border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer inline-flex items-center justify-center gap-1.5">
                                <x-icon name="sparkle" class="w-3 h-3"/>
                                {{ $editMerch ? 'Simpan Perubahan' : 'Simpan ke Katalog' }}
                            </button>
                        </form>
                    </section>

                    <!-- Bento 2: Tambah Kategori Fandom Baru -->
                    <section class="bg-[#010310] text-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#23A9BD]">
                        <h3 class="font-nohemi font-bold text-sm mb-2 flex items-center gap-1.5">
                            <x-icon name="sparkle" class="w-3 h-3"/> Tambah Fandom Series Baru
                        </h3>
                        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex items-center gap-1.5">
                            @csrf
                            <input type="text" name="name" required placeholder="Contoh: Honkai Star Rail..."
                                   class="flex-1 px-3 py-1.5 rounded-full bg-white/10 border border-white/25 text-[11px] text-white placeholder:text-white/40 outline-none focus:border-[#23A9BD]">
                            <button type="submit"
                                    class="px-3.5 py-1.5 rounded-full bg-[#23A9BD] text-white font-bold text-[11px] cursor-pointer">
                                + Add
                            </button>
                        </form>
                        @error('name') <span class="text-[10px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </section>
                </div>

                <!-- KOLOM KANAN (8 Kolom): Tabel & Progress Kuota Merchandise -->
                <div class="lg:col-span-8 flex">
                    <section class="w-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xs font-bold text-[#010310]">Daftar Katalog &amp; Kuota Aktif ({{ $merchandises->count() }} Item)</h3>
                                <span class="text-[10px] font-bold text-[#23A9BD]">Total Sisa Kuota: {{ $merchandises->sum('available_quota') }} Slot</span>
                            </div>

                            <div class="overflow-x-auto no-scrollbar">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-[#010310]/15 text-[9px] font-extrabold uppercase text-[#010310]/50">
                                            <th class="pb-2">Nama Item</th>
                                            <th class="pb-2">Fandom &amp; Tipe</th>
                                            <th class="pb-2">Harga</th>
                                            <th class="pb-2">Sisa Kuota</th>
                                            <th class="pb-2 text-right">Aksi CRUD</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#010310]/10 text-[11px]">
                                        @forelse($merchandises as $item)
                                            <tr class="hover:bg-[#F8F9FA] transition">
                                                <td class="py-2.5 font-bold text-[#010310] flex items-center gap-2">
                                                    @if($item->imageUrl())
                                                        <img src="{{ $item->imageUrl() }}" alt="Gambar {{ $item->name }}"
                                                             class="w-9 h-9 object-cover rounded-xl border border-[#010310]/20 flex-shrink-0">
                                                    @else
                                                        <span class="w-9 h-9 rounded-xl bg-[#F8F9FA] border border-[#010310]/15 flex items-center justify-center flex-shrink-0 text-[#010310]/60">
                                                            <x-icon :name="$item->iconName()" class="w-4 h-4"/>
                                                        </span>
                                                    @endif
                                                    <span class="truncate">{{ $item->name }}</span>
                                                </td>
                                                <td class="py-2.5">
                                                    <span class="px-2 py-0.5 rounded-full bg-[#23A9BD]/15 text-[#23A9BD] text-[9px] font-bold">
                                                        {{ $item->category->name }}
                                                    </span>
                                                    <span class="text-[10px] text-[#010310]/50 ml-1 uppercase">{{ str_replace('_', ' ', $item->item_type) }}</span>
                                                </td>
                                                <td class="py-2.5 font-bold text-[#010310]">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="py-2.5">
                                                    <span title="Kuota cetak: {{ $item->stock_quota }} slot"
                                                          class="px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $item->available_quota > 10 ? 'bg-emerald-100 text-emerald-800' : ($item->available_quota > 0 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-700') }}">
                                                        {{ $item->available_quota }} slot
                                                    </span>
                                                </td>
                                                <td class="py-2.5 text-right">
                                                    <div class="inline-flex items-center gap-1.5">
                                                        <a href="{{ route('admin.merchandises', ['edit' => $item->id]) }}"
                                                           class="px-2.5 py-1 rounded-full border border-[#010310] bg-[#F8F9FA] hover:bg-[#23A9BD] hover:text-white text-[10px] font-bold transition">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('admin.merchandises.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus merchandise ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="px-2.5 py-1 rounded-full border border-[#010310] bg-white hover:bg-rose-500 hover:text-white text-[10px] font-bold transition cursor-pointer">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-8 text-center text-xs text-[#010310]/50">
                                                    Belum ada merchandise di kategori ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

            </div>

        </main>
    </div>

</body>
</html>