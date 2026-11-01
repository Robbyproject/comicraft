<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $character['name'] ?? 'Character Showcase' }} - ComiCraft</title>

    <!-- Tailwind CSS v4 & Fonts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- GSAP via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #010310;
        }
        .neo-shadow {
            box-shadow: 4px 4px 0px #010310;
        }
        .neo-shadow-hover:hover {
            transform: translate(-3px, -3px);
            box-shadow: 7px 7px 0px #23A9BD;
        }

        /* HILANGKAN SCROLLBAR TOTAL DI SEMUA BROWSER */
        html, body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        ::-webkit-scrollbar {
            display: none !important;
            width: 0px !important;
            height: 0px !important;
            background: transparent;
        }
    </style>
</head>
<body class="bg-[#010310] text-[#010310] antialiased overflow-x-hidden selection:bg-[#23A9BD] selection:text-white">

    @php
        $activeKey = $characterKey ?? $character['slug'] ?? request()->segment(2) ?? 'shiroko';
    @endphp

    <!-- ========================================================== -->
    <!-- 1. FLOATING NAVIGATION BAR DENGAN COLLAB RESMI TRANSPARAN  -->
    <!-- ========================================================== -->
    <header class="fixed top-0 left-0 w-full z-50 px-6 md:px-10 py-4 flex flex-wrap items-center justify-between gap-3 pointer-events-none">
        
        <!-- Tombol Kembali -->
        <a href="{{ url('/katalog') }}" 
           class="pointer-events-auto inline-flex items-center gap-2 px-4 py-2 bg-[#010310]/80 backdrop-blur-md text-white font-bold text-[11px] uppercase tracking-wider rounded-full border border-white/20 hover:border-[#23A9BD] hover:text-[#23A9BD] transition-all shadow-md">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Katalog
        </a>

        <!-- TOMBOL PEMILIH 4 KARAKTER LIVE2D -->
        <div class="pointer-events-auto flex items-center gap-1.5 bg-[#010310]/85 backdrop-blur-md p-1.5 rounded-full border border-white/20 shadow-xl">
            @foreach(['shiroko' => 'Shiroko', 'yuuka' => 'Yuuka', 'saori' => 'Saori', 'midori' => 'Midori'] as $key => $name)
                <a href="{{ url('/showcase/' . $key) }}" 
                   class="px-3.5 py-1 text-xs font-black uppercase rounded-full transition-all duration-200 {{ $activeKey === $key ? 'bg-[#23A9BD] text-white shadow-[2px_2px_0px_#010310]' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                    {{ $name }}
                </a>
            @endforeach
        </div>

        <!-- LOGO COLLAB RESMI (100% TRANSPARAN TANPA SHAPE HITAM) -->
        <div class="pointer-events-auto flex items-center gap-2.5 bg-transparent p-0">
            <!-- Logo Asli ComiCraft dari public/images/ComiCraft-Logo.png -->
            <img src="{{ asset('images/ComiCraft-Logo.png') }}" 
                 alt="ComiCraft" 
                 class="h-6 sm:h-7 w-auto object-contain drop-shadow-[0_2px_8px_rgba(0,0,0,0.85)]">
            
            <span class="text-xs sm:text-sm font-black text-[#23A9BD] drop-shadow-[0_2px_4px_rgba(0,0,0,0.85)]">✕</span>
            
            <!-- Logo Blue Archive dari public/ -->
            <img src="{{ asset('blue-archive-seeklogo.png') }}" 
                 alt="Blue Archive" 
                 class="h-6 sm:h-7 w-auto object-contain drop-shadow-[0_2px_8px_rgba(0,0,0,0.85)]" 
                 onerror="this.src='{{ asset('blue-archive-seeklogo.svg') }}'">
            
            <div class="w-px h-4 bg-white/30 mx-0.5"></div>
            <span class="text-white text-[11px] sm:text-xs font-extrabold tracking-wider uppercase drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)]">
                Booth B-12
            </span>
        </div>

    </header>


    <!-- ========================================================== -->
    <!-- 2. FULL-SCREEN LIVE2D BACKGROUND                           -->
    <!-- ========================================================== -->
    <section class="relative w-full h-screen overflow-hidden flex items-end bg-[#070913]">
        
        <!-- Live2D Iframe Dinamis -->
        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden flex items-center justify-center pointer-events-none">
            <iframe 
                src="{{ asset('l2d/' . $activeKey . '/index.html') }}" 
                class="w-full h-full min-w-full min-h-full border-none pointer-events-none scale-[1.02]"
                loading="eager">
            </iframe>
        </div>

        <!-- Gradien halus -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#010310] via-[#010310]/40 to-transparent pointer-events-none z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#010310]/70 via-transparent to-transparent pointer-events-none z-10"></div>

        <!-- Konten Kiri Bawah -->
        <div class="relative z-20 w-full max-w-7xl mx-auto px-6 md:px-10 pb-8 md:pb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            
            <div class="max-w-xl">
                <!-- Subtitle / Faksi -->
                <div class="hero-sub inline-flex items-center gap-2 text-[11px] md:text-xs font-black tracking-widest text-[#23A9BD] uppercase mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#23A9BD] animate-pulse"></span>
                    {{ $character['sub'] ?? 'Abydos High School • Foreclosure Task Force' }}
                </div>

                <!-- Judul Karakter Ramping -->
                <h1 class="hero-title text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-md">
                    {{ $character['name'] ?? 'Shiroko Sunaookami' }}
                </h1>

                <!-- Deskripsi -->
                <p class="hero-desc text-xs md:text-sm text-gray-300 font-normal mt-2 leading-relaxed max-w-md">
                    {{ $character['desc'] ?? 'Koleksi fanart eksklusif convention batch ini. Amankan merchandise resmi booth ComiCraft sebelum kuota pre-order terpenuhi.' }}
                </p>

                <!-- Tombol CTA Scroll -->
                <div class="hero-cta mt-4 flex items-center gap-3">
                    <a href="#merchandise-section" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#23A9BD] hover:bg-[#1e93a5] text-white font-extrabold text-xs uppercase tracking-wider rounded-lg border border-white/80 shadow-[3px_3px_0px_#ffffff] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        Lihat Koleksi Merchandise
                        <svg class="w-3.5 h-3.5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Mini Counter -->
            <div class="hero-stat hidden md:flex flex-col items-end text-right">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Total Varian Fanart</span>
                <span class="text-2xl md:text-3xl font-black text-white font-mono">
                    {{ count($merchandises) }} <span class="text-sm font-bold text-[#23A9BD]">Items</span>
                </span>
                <span class="text-[10px] text-gray-400">Siap Pick-up di Booth Comifuro 19</span>
            </div>

        </div>

    </section>


    <!-- ========================================================== -->
    <!-- 3. MERCHANDISE SECTION                                     -->
    <!-- ========================================================== -->
    <main id="merchandise-section" class="relative z-30 bg-[#F8F9FA] border-t-3 border-[#010310] pt-10 pb-20 px-6 md:px-10 lg:px-14 min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            <!-- Section Header -->
            <div class="section-header flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b-2 border-gray-200">
                <div>
                    <span class="px-2.5 py-0.5 bg-[#010310] text-[#23A9BD] text-[10px] font-black uppercase tracking-wider rounded">
                        Official Fanart Batch
                    </span>
                    <h2 class="text-2xl md:text-3xl font-black text-[#010310] tracking-tight mt-2">
                        Koleksi Merchandise {{ explode(' ', $character['name'] ?? 'Karakter')[0] }}
                    </h2>
                    <p class="text-xs md:text-sm text-gray-600 font-medium mt-1">
                        Pilih produk favorit Anda, amankan kuota slot, dan ambil langsung di booth convention tanpa antre.
                    </p>
                </div>

                <!-- Counter Badge -->
                <div>
                    <span class="px-3.5 py-1.5 bg-[#010310] text-white text-[11px] font-bold rounded-lg neo-shadow">
                        Semua Produk ({{ count($merchandises) }})
                    </span>
                </div>
            </div>

            <!-- Merchandise Cards Grid -->
            @if(count($merchandises) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @foreach($merchandises as $item)
                        <div class="merch-card bg-white border-2 border-[#010310] rounded-xl p-5 neo-shadow neo-shadow-hover transition-all duration-200 flex flex-col justify-between">
                            
                            <div>
                                <!-- Card Header -->
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 bg-amber-50 text-amber-900 border border-amber-300 text-[10px] font-extrabold uppercase rounded tracking-wider">
                                        {{ str_replace('_', ' ', strtoupper($item->item_type)) }}
                                    </span>

                                    @php
                                        $quotaLeft = $item->stock_quota - ($item->reserved_quota ?? 0);
                                    @endphp
                                    <span class="text-[11px] font-bold {{ $quotaLeft > 10 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        Slot Sisa: {{ $quotaLeft }}/{{ $item->stock_quota }}
                                    </span>
                                </div>

                                <!-- Image Placeholder -->
                                <div class="relative w-full h-44 bg-gradient-to-br from-gray-100 to-gray-200 border border-[#010310] rounded-lg overflow-hidden flex items-center justify-center mb-4 group">
                                    @if($item->imageUrl())
                                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    @else
                                        <div class="flex flex-col items-center justify-center text-gray-400 group-hover:text-[#23A9BD] transition-colors">
                                            <svg class="w-10 h-10 stroke-current stroke-1" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-[10px] font-bold tracking-wider mt-1 uppercase">Official Fanart</span>
                                        </div>
                                    @endif

                                    <!-- Price Pill -->
                                    <div class="absolute bottom-2.5 right-2.5 px-3 py-1 bg-[#010310] text-white text-xs font-black rounded neo-shadow">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Nama Merchandise -->
                                <h3 class="text-base font-extrabold text-[#010310] leading-snug line-clamp-2 mb-1">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    Kategori: {{ $item->category->name ?? 'Blue Archive' }}
                                </p>
                            </div>

                            <!-- Tombol Action Pre-Order -->
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <a href="{{ url('/preorder/' . $item->id) }}" 
                                   class="w-full py-2.5 bg-[#23A9BD] hover:bg-[#1e91a3] text-white text-[11px] font-black uppercase tracking-wider rounded-lg border border-[#010310] shadow-[2px_2px_0px_#010310] flex items-center justify-center gap-1.5 transition-transform active:translate-y-0.5">
                                    Pre-Order Item Ini
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-10 text-center py-12 bg-white border border-dashed border-gray-300 rounded-2xl">
                    <p class="text-sm font-bold text-gray-500">Belum ada merchandise untuk karakter ini dalam batch sekarang.</p>
                    <a href="{{ url('/katalog') }}" class="inline-block mt-3 px-5 py-2 bg-[#010310] text-white text-xs font-bold rounded-lg">
                        Kembali ke Katalog Utama
                    </a>
                </div>
            @endif

        </div>
    </main>


    <!-- ========================================================== -->
    <!-- 4. GSAP TEKS HERO PEMBUKA                                  -->
    <!-- ========================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const heroTl = gsap.timeline({ defaults: { ease: "power3.out" } });

            heroTl
                .from(".hero-sub", { y: 20, opacity: 0, duration: 0.6, delay: 0.1 })
                .from(".hero-title", { y: 30, opacity: 0, duration: 0.8 }, "-=0.4")
                .from(".hero-desc", { y: 15, opacity: 0, duration: 0.6 }, "-=0.5")
                .from(".hero-cta", { scale: 0.95, opacity: 0, duration: 0.5 }, "-=0.4")
                .from(".hero-stat", { opacity: 0, x: 20, duration: 0.6 }, "-=0.5");
        });
    </script>

</body>
</html>