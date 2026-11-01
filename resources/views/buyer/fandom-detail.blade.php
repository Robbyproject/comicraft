<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} Merch Collection - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="buyer-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen min-h-dvh flex flex-col selection:bg-[#23A9BD] selection:text-white">

    <!-- Navbar Buyer -->
    @include('buyer.partials.navbar')

    <main class="w-full max-w-6xl mx-auto px-6 py-6 flex-1 flex flex-col gap-6">

        <!-- 1. HERO BANNER LEBAR SINEMATIK FANDOM -->
        <div class="gsap-buyer-item w-full rounded-[28px] border-[1.5px] border-[#010310] shadow-[3.5px_3.5px_0px_#010310] overflow-hidden relative bg-[#010310] text-white">
            
            @if($category->bannerUrl())
                <!-- Banner Asli Jika Sudah Diunggah -->
                <img src="{{ $category->bannerUrl() }}" alt="{{ $category->name }}"
                     class="w-full h-44 sm:h-56 md:h-64 object-cover opacity-60">
            @else
                <!-- Fallback Banner Bergaya Neo-Pop Anime yang Estetik -->
                <div class="w-full h-44 sm:h-56 md:h-64 bg-gradient-to-r from-[#010310] via-[#010310]/95 to-[#23A9BD]/30 flex items-center justify-between px-6 sm:px-10 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-10 opacity-15 text-8xl font-black select-none pointer-events-none">
                        ✦
                    </div>
                </div>
            @endif

            <!-- Konten Overlay Teks di Atas Banner -->
            <div class="absolute inset-0 p-6 sm:p-8 flex flex-col justify-between z-10">
                <!-- Baris Atas: Tombol Back & Badge -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('katalog') }}"
                       class="px-3.5 py-1.5 rounded-full bg-white text-[#010310] font-bold text-[11px] border border-[#010310] shadow-[2px_2px_0px_#010310] hover:bg-[#23A9BD] hover:text-white transition flex items-center gap-1.5">
                        ← Semua Fandom
                    </a>
                    <span class="px-3 py-1 rounded-full bg-[#23A9BD] text-white font-black text-[10px] uppercase tracking-wider border border-white/20">
                        Booth #B-12 Official
                    </span>
                </div>

                <!-- Baris Bawah: Judul Anime & Ringkasan Slot -->
                <div>
                    <span class="text-[10px] font-extrabold uppercase text-[#23A9BD] tracking-widest block mb-1">
                        Fandom Collection
                    </span>
                    <h1 class="font-nohemi font-bold text-3xl sm:text-4xl md:text-5xl tracking-tight text-white leading-none">
                        {{ $category->name }}
                    </h1>
                    <p class="text-xs text-white/70 font-medium mt-2 flex items-center gap-2">
                        <span>{{ $merchandises->count() }} Merchandise Tersedia</span> •
                        <span class="text-emerald-400 font-bold">Total {{ $merchandises->sum('stock_quota') }} Slot Pre-Order</span>
                    </p>
                </div>
            </div>
        </div>

        @include('buyer.partials.flash')

        <!-- 2. NAVIGASI CHIPS FANDOM LAINNYA -->
        <div class="gsap-buyer-item flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
            <span class="text-[11px] font-bold text-[#010310]/50 uppercase flex-shrink-0 mr-1">Pilih Fandom:</span>
            <a href="{{ route('katalog') }}"
               class="px-3 py-1 rounded-full text-xs font-bold border border-[#010310] bg-white text-[#010310] hover:bg-slate-100 transition flex-shrink-0">
                Semua
            </a>
            @foreach($allCategories as $cat)
                <a href="{{ route('fandom.detail', $cat->slug) }}"
                   class="px-3.5 py-1 rounded-full text-xs font-bold border border-[#010310] flex-shrink-0 transition
                          {{ $cat->id === $category->id ? 'bg-[#010310] text-white shadow-[2px_2px_0px_#23A9BD]' : 'bg-white text-[#010310] hover:bg-[#23A9BD]/15' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- 3. GRID MERCHANDISE SPESIFIK FANDOM INI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-8">
            @forelse($merchandises as $merch)
                <section class="gsap-buyer-card bg-white border-[1.5px] border-[#010310] rounded-2xl shadow-[2.5px_2.5px_0px_#010310] overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Visual Produk -->
                        <div class="h-36 bg-[#F8F9FA] border-b-[1.5px] border-[#010310] flex items-center justify-center overflow-hidden">
                            @if($merch->imageUrl())
                                <img src="{{ $merch->imageUrl() }}" alt="{{ $merch->name }}" class="w-full h-full object-cover">
                            @else
                                <x-icon :name="$merch->iconName()" class="w-10 h-10 text-[#010310]/30"/>
                            @endif
                        </div>

                        <!-- Info Produk -->
                        <div class="p-4 flex flex-col gap-2">
                            <h2 class="text-xs font-bold text-[#010310] leading-snug line-clamp-2">{{ $merch->name }}</h2>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[9px] uppercase font-semibold text-[#010310]/60 inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-full">
                                    <x-icon :name="$merch->iconName()" class="w-2.5 h-2.5"/>
                                    {{ str_replace('_', ' ', $merch->item_type) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-[#010310]/10">
                                <p class="font-nohemi font-bold text-lg text-[#010310] leading-none">
                                    Rp {{ number_format($merch->price, 0, ',', '.') }}
                                </p>
                                <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $merch->available_quota > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $merch->available_quota }} slot
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Pesan -->
                    <div class="p-4 pt-0">
                        <a href="{{ route('preorder.create', $merch) }}"
                           class="w-full py-2 rounded-full font-nohemi font-bold text-[11px] uppercase tracking-wider border-[1.5px] border-[#010310] transition text-center inline-flex items-center justify-center gap-1.5 bg-[#23A9BD] text-white shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px]">
                            Pre-Order Sekarang
                            <x-icon name="sparkle" class="w-3 h-3"/>
                        </a>
                    </div>
                </section>
            @empty
                <div class="col-span-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-8 text-center shadow-[2.5px_2.5px_0px_#010310]">
                    <p class="text-2xl mb-1">📦</p>
                    <p class="font-nohemi font-bold text-sm text-[#010310]">Belum ada item untuk fandom {{ $category->name }}</p>
                    <p class="text-[11px] text-[#010310]/50 mt-0.5">Kreator sedang menyiapkan batch fanart terbaru.</p>
                </div>
            @endforelse
        </div>

    </main>

</body>
</html>