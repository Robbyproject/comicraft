<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Merchandise - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="buyer-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen min-h-dvh flex flex-col selection:bg-[#23A9BD] selection:text-white">

    <!-- Navbar Buyer Transparan -->
    @include('buyer.partials.navbar')

    <main class="w-full max-w-6xl mx-auto px-6 py-4 flex-1 flex flex-col gap-6">

        <!-- 1. HEADER BANNER TERBUKA -->
        <div class="gsap-buyer-item w-full py-4 sm:py-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-[#010310]/15">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-[#010310] bg-white text-[10px] font-extrabold uppercase mb-2 shadow-[2px_2px_0px_#010310]">
                    <span>✨</span> Convention Batch #1 Official Catalog
                </div>
                <h1 class="font-nohemi font-bold text-3xl sm:text-4xl text-[#010310] tracking-tight leading-none">
                    Katalog Pre-Order Merchandise
                </h1>
                <p class="text-xs text-[#010310]/60 font-medium mt-2 max-w-xl">
                    Amankan standee akrilik, gantungan kunci, dan poster anime favoritmu. Slot tersisa dihitung langsung dari pemesanan aktif.
                </p>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="px-3.5 py-1.5 rounded-full bg-[#010310] text-white text-xs font-bold">
                    Booth #B-12 Ready
                </span>
                <span class="px-3.5 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-[#010310]">
                    🛡️ Escrow Verified
                </span>
            </div>
        </div>

        @include('buyer.partials.flash')

        <!-- 2. KARTU PILIHAN LIVE2D CHARACTER SPOTLIGHT (LOGO MURNI TANPA SHAPE & TANPA TEKS) -->
        <div class="gsap-buyer-item flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-[#010310] flex items-center gap-2">
                    <img src="{{ asset('blue-archive-seeklogo.svg') }}" alt="Blue Archive" class="h-4 w-auto object-contain" onerror="this.src='{{ asset('blue-archive-seeklogo.png') }}'">
                    Live2D Character Spotlight (Klik untuk Animasi Penuh)
                </span>
                <span class="text-[11px] font-bold text-[#010310]/50 hidden sm:inline">Convention Batch #1</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                <!-- 1. Shiroko -->
                <a href="{{ route('character.showcase', 'shiroko') }}" 
                   class="group bg-white border-2 border-[#010310] rounded-2xl p-3 shadow-[3px_3px_0px_#010310] hover:-translate-y-1 hover:shadow-[5px_5px_0px_#23A9BD] transition-all flex flex-col justify-between overflow-hidden">
                    <div>
                        <!-- Gambar Preview Shiroko -->
                        <div class="w-full h-28 rounded-xl border border-[#010310] overflow-hidden mb-2.5 bg-slate-100 relative">
                            <img src="{{ asset('l2d/shiroko/preview.jpg') }}" alt="Shiroko Sunaookami" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                 onerror="this.onerror=null; this.src='{{ asset('l2d/shiroko/preview.png') }}';">
                            <!-- Logo Blue Archive Murni Mengambang Transparan -->
                            <img src="{{ asset('blue-archive-seeklogo.svg') }}" alt="Blue Archive" 
                                 class="absolute top-2 left-2 h-4 sm:h-4.5 w-auto object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.75)]" 
                                 onerror="this.src='{{ asset('blue-archive-seeklogo.png') }}'">
                        </div>
                        <h3 class="font-nohemi font-bold text-sm text-[#010310] group-hover:text-[#23A9BD] transition-colors leading-tight">
                            Shiroko Sunaookami
                        </h3>
                        <p class="text-[11px] text-[#010310]/60 mt-0.5 line-clamp-1">Foreclosure Task Force</p>
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-[#010310]/10 flex items-center justify-between text-[11px] font-extrabold text-[#23A9BD]">
                        <span>Buka Live2D</span>
                        <span class="group-hover:translate-x-1 transition-transform">➔</span>
                    </div>
                </a>

                <!-- 2. Yuuka -->
                <a href="{{ route('character.showcase', 'yuuka') }}" 
                   class="group bg-white border-2 border-[#010310] rounded-2xl p-3 shadow-[3px_3px_0px_#010310] hover:-translate-y-1 hover:shadow-[5px_5px_0px_#23A9BD] transition-all flex flex-col justify-between overflow-hidden">
                    <div>
                        <!-- Gambar Preview Yuuka -->
                        <div class="w-full h-28 rounded-xl border border-[#010310] overflow-hidden mb-2.5 bg-slate-100 relative">
                            <img src="{{ asset('l2d/yuuka/preview.gif') }}" alt="Yuuka Hayase" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <!-- Logo Blue Archive Murni Mengambang Transparan -->
                            <img src="{{ asset('blue-archive-seeklogo.svg') }}" alt="Blue Archive" 
                                 class="absolute top-2 left-2 h-4 sm:h-4.5 w-auto object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.75)]" 
                                 onerror="this.src='{{ asset('blue-archive-seeklogo.png') }}'">
                        </div>
                        <h3 class="font-nohemi font-bold text-sm text-[#010310] group-hover:text-[#23A9BD] transition-colors leading-tight">
                            Yuuka Hayase
                        </h3>
                        <p class="text-[11px] text-[#010310]/60 mt-0.5 line-clamp-1">Seminar Treasurer</p>
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-[#010310]/10 flex items-center justify-between text-[11px] font-extrabold text-[#23A9BD]">
                        <span>Buka Live2D</span>
                        <span class="group-hover:translate-x-1 transition-transform">➔</span>
                    </div>
                </a>

                <!-- 3. Saori -->
                <a href="{{ route('character.showcase', 'saori') }}" 
                   class="group bg-white border-2 border-[#010310] rounded-2xl p-3 shadow-[3px_3px_0px_#010310] hover:-translate-y-1 hover:shadow-[5px_5px_0px_#23A9BD] transition-all flex flex-col justify-between overflow-hidden">
                    <div>
                        <!-- Gambar Preview Saori -->
                        <div class="w-full h-28 rounded-xl border border-[#010310] overflow-hidden mb-2.5 bg-slate-100 relative">
                            <img src="{{ asset('l2d/saori/preview.gif') }}" alt="Saori Joumae" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <!-- Logo Blue Archive Murni Mengambang Transparan -->
                            <img src="{{ asset('blue-archive-seeklogo.svg') }}" alt="Blue Archive" 
                                 class="absolute top-2 left-2 h-4 sm:h-4.5 w-auto object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.75)]" 
                                 onerror="this.src='{{ asset('blue-archive-seeklogo.png') }}'">
                        </div>
                        <h3 class="font-nohemi font-bold text-sm text-[#010310] group-hover:text-[#23A9BD] transition-colors leading-tight">
                            Saori Joumae
                        </h3>
                        <p class="text-[11px] text-[#010310]/60 mt-0.5 line-clamp-1">Arius Squad Leader</p>
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-[#010310]/10 flex items-center justify-between text-[11px] font-extrabold text-[#23A9BD]">
                        <span>Buka Live2D</span>
                        <span class="group-hover:translate-x-1 transition-transform">➔</span>
                    </div>
                </a>

                <!-- 4. Midori -->
                <a href="{{ route('character.showcase', 'midori') }}" 
                   class="group bg-white border-2 border-[#010310] rounded-2xl p-3 shadow-[3px_3px_0px_#010310] hover:-translate-y-1 hover:shadow-[5px_5px_0px_#23A9BD] transition-all flex flex-col justify-between overflow-hidden">
                    <div>
                        <!-- Gambar Preview Midori -->
                        <div class="w-full h-28 rounded-xl border border-[#010310] overflow-hidden mb-2.5 bg-slate-100 relative">
                            <img src="{{ asset('l2d/midori/preview.gif') }}" alt="Midori Saiba" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <!-- Logo Blue Archive Murni Mengambang Transparan -->
                            <img src="{{ asset('blue-archive-seeklogo.svg') }}" alt="Blue Archive" 
                                 class="absolute top-2 left-2 h-4 sm:h-4.5 w-auto object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.75)]" 
                                 onerror="this.src='{{ asset('blue-archive-seeklogo.png') }}'">
                        </div>
                        <h3 class="font-nohemi font-bold text-sm text-[#010310] group-hover:text-[#23A9BD] transition-colors leading-tight">
                            Midori Saiba
                        </h3>
                        <p class="text-[11px] text-[#010310]/60 mt-0.5 line-clamp-1">Game Development Club</p>
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-[#010310]/10 flex items-center justify-between text-[11px] font-extrabold text-[#23A9BD]">
                        <span>Buka Live2D</span>
                        <span class="group-hover:translate-x-1 transition-transform">➔</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3. FILTER FANDOM LANGSUNG DI HALAMAN INI -->
        <div class="gsap-buyer-item flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
            <span class="text-[11px] font-bold text-[#010310]/50 uppercase flex-shrink-0 mr-1">Filter Fandom:</span>
            
            <a href="{{ route('katalog', ['fandom' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-bold border border-[#010310] transition flex-shrink-0
                      {{ $selectedFandom === 'all' ? 'bg-[#010310] text-white shadow-[2px_2px_0px_#23A9BD]' : 'bg-white text-[#010310] hover:bg-slate-100' }}">
                Semua Koleksi
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('katalog', ['fandom' => $cat->slug]) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold border border-[#010310] transition flex-shrink-0
                          {{ $selectedFandom === $cat->slug ? 'bg-[#23A9BD] text-white shadow-[2px_2px_0px_#010310]' : 'bg-white text-[#010310] hover:bg-[#23A9BD]/15' }}">
                    {{ $cat->name }} ({{ $cat->merchandises_count }})
                </a>
            @endforeach
        </div>

        <!-- 4. GRID KARTU MERCHANDISE (BEBAS DARI TOMBOL SPOTLIGHT KARAKTER) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-8">
            @forelse($merchandises as $merch)
                <section class="gsap-buyer-card bg-white border-[1.5px] border-[#010310] rounded-2xl shadow-[2.5px_2.5px_0px_#010310] overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Visual Thumbnail Produk Asli -->
                        <div class="h-40 bg-[#F8F9FA] border-b-[1.5px] border-[#010310] flex items-center justify-center overflow-hidden relative group">
                            @if($merch->imageUrl())
                                <img src="{{ $merch->imageUrl() }}" alt="{{ $merch->name }}" class="w-full h-full object-cover">
                            @else
                                <x-icon :name="$merch->iconName()" class="w-12 h-12 text-[#010310]/30"/>
                            @endif
                        </div>

                        <!-- Info Produk -->
                        <div class="p-4 flex flex-col gap-2">
                            <h2 class="text-xs font-bold text-[#010310] leading-snug line-clamp-2">{{ $merch->name }}</h2>
                            
                            <!-- Badge Fandom -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                <a href="{{ route('katalog', ['fandom' => $merch->category->slug]) }}"
                                   class="px-2.5 py-0.5 rounded-full bg-[#23A9BD]/15 text-[#23A9BD] hover:bg-[#23A9BD] hover:text-white transition text-[9px] font-bold"
                                   title="Filter hanya fandom ini">
                                    ✦ {{ $merch->category->name }}
                                </a>
                                <span class="text-[9px] uppercase font-semibold text-[#010310]/50 inline-flex items-center gap-1">
                                    <x-icon :name="$merch->iconName()" class="w-2.5 h-2.5"/>
                                    {{ str_replace('_', ' ', $merch->item_type) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-[#010310]/10">
                                <p class="font-nohemi font-bold text-lg text-[#010310] leading-none">
                                    Rp {{ number_format($merch->price, 0, ',', '.') }}
                                </p>
                                <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $merch->available_quota > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $merch->available_quota }} slot tersisa
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Pre-Order -->
                    <div class="p-4 pt-0">
                        <a href="{{ route('preorder.create', $merch) }}"
                           {{ $merch->available_quota ? '' : 'aria-disabled="true" tabindex="-1"' }}
                           class="w-full py-2.5 rounded-full font-nohemi font-bold text-[11px] uppercase tracking-wider border-[1.5px] border-[#010310] transition text-center inline-flex items-center justify-center gap-1.5 {{ $merch->available_quota ? 'bg-[#23A9BD] text-white shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px]' : 'bg-[#F8F9FA] text-[#010310]/40 border-[#010310]/20 pointer-events-none' }}">
                            {{ $merch->available_quota ? 'Pre-Order Sekarang' : 'Slot Habis' }}
                            <x-icon :name="$merch->available_quota ? 'sparkle' : 'x-mark'" class="w-3 h-3"/>
                        </a>
                    </div>
                </section>
            @empty
                <div class="col-span-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-10 text-center shadow-[2.5px_2.5px_0px_#010310]">
                    <p class="text-3xl mb-1">📦</p>
                    <p class="font-nohemi font-bold text-sm text-[#010310]">Tidak ada merchandise pada filter fandom ini</p>
                    <a href="{{ route('katalog', ['fandom' => 'all']) }}" class="text-[11px] text-[#23A9BD] font-bold underline mt-1 inline-block">
                        Lihat semua koleksi merchandise ➔
                    </a>
                </div>
            @endforelse
        </div>

    </main>

</body>
</html>