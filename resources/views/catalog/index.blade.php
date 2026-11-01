<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Merchandise - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen selection:bg-[#23A9BD] selection:text-white flex flex-col justify-between">

    <!-- Navbar -->
    <header class="w-full max-w-6xl mx-auto px-6 py-4 flex items-center justify-between border-b border-[#010310]/10">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/ComiCraft-Logo.png') }}" alt="ComiCraft" class="h-8 w-auto">
        </a>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('buyer.orders') }}"
                   class="px-4 py-1.5 rounded-full text-xs font-bold border border-[#010310] bg-white hover:bg-[#010310] hover:text-white transition">
                    Tiket Pre-Order Saya 🎟️
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-full text-xs font-bold border border-[#010310] bg-white">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-full text-xs font-bold bg-[#23A9BD] text-white">Daftar</a>
            @endauth
        </div>
    </header>

    <!-- Konten Katalog -->
    <main class="w-full max-w-6xl mx-auto px-6 py-8 flex-1">
        <!-- Notifikasi -->
        @if(session('error'))
            <div class="mb-5 p-3 rounded-xl border border-[#010310] bg-rose-50 text-rose-600 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="font-nohemi font-bold text-3xl sm:text-4xl text-[#010310]">Merchandise Artist Alley</h1>
                <p class="text-xs text-[#010310]/60 font-medium mt-1">Pilih fanart favoritmu dan amankan kuota cetaknya sebelum kehabisan!</p>
            </div>

            <!-- Filter Kategori Fandom -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('catalog.index') }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold border border-[#010310] {{ !request('fandom') ? 'bg-[#010310] text-white' : 'bg-white text-[#010310]' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', ['fandom' => $cat->slug]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold border border-[#010310] {{ request('fandom') === $cat->slug ? 'bg-[#23A9BD] text-white' : 'bg-white text-[#010310]' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Grid Barang -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($merchandises as $item)
                <div class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-5 shadow-[3px_3px_0px_#010310] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#23A9BD]/15 text-[#23A9BD]">
                                {{ $item->category->name }}
                            </span>
                            <span class="text-slate-400 uppercase text-[10px]">{{ str_replace('_', ' ', $item->item_type) }}</span>
                        </div>

                        <!-- Gambar / Placeholder -->
                        <div class="w-full h-44 bg-[#F8F9FA] border border-[#010310]/15 rounded-xl mb-4 flex items-center justify-center text-4xl">
                            🎨
                        </div>

                        <h3 class="font-nohemi font-bold text-lg text-[#010310] mb-1.5 leading-snug">{{ $item->name }}</h3>
                        <p class="text-xs font-bold text-emerald-700 mb-4">Sisa Kuota: {{ $item->stock_quota }} slot</p>
                    </div>

                    <!-- Form Pesan Pre-Order Cepat -->
                    <div class="pt-4 border-t border-[#010310]/10">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-[#010310]/50 font-semibold">Harga PO</span>
                            <span class="font-nohemi font-bold text-xl text-[#010310]">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>

                        <form action="{{ route('preorder.store', $item->id) }}" method="POST" class="space-y-2">
                            @csrf
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <label class="block text-[10px] font-bold text-[#010310]/60 uppercase mb-0.5">Jumlah</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $item->stock_quota }}" required
                                           class="w-full px-2.5 py-1 rounded-lg border border-[#010310]/30 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-[#010310]/60 uppercase mb-0.5">Metode</label>
                                    <select name="pickup_method" class="w-full px-1.5 py-1 rounded-lg border border-[#010310]/30 font-semibold">
                                        <option value="booth_pickup">Ambil Booth</option>
                                        <option value="shipping">Ekspedisi</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit"
                                    class="w-full py-2 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase tracking-wider border border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer">
                                Pesan Pre-Order ✦
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-xs text-[#010310]/50 font-semibold">
                    Belum ada merchandise aktif di kategori ini.
                </div>
            @endforelse
        </div>
    </main>

    <footer class="w-full py-4 text-center text-xs text-[#010310]/50 border-t border-[#010310]/10">
        © 2026 ComiCraft. Sistem Manajemen Operasional Pre-Order &amp; Booth Artist Alley.
    </footer>

</body>
</html>