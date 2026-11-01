<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComiCraft - Artist Alley Anime Pre-Order Ops</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="hero-body"
      class="bg-[#F8F9FA] text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col selection:bg-[#23A9BD] selection:text-white bg-no-repeat"
      style="background-image: url('{{ asset('images/Hero-1.png') }}'); background-position: center bottom -11vh; background-size: 100% auto;">

    <!-- 1. NAVBAR -->
    <header class="gsap-nav w-full max-w-6xl mx-auto px-6 py-4 flex items-center justify-between relative z-20 bg-transparent">
        <!-- Logo Gambar -->
        <a href="{{ route('home') }}" class="flex items-center group">
            <img src="{{ asset('images/ComiCraft-Logo.png') }}"
                 alt="ComiCraft Logo"
                 class="h-8 sm:h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
        </a>

        <!-- Menu Navigasi Tengah (Katalog & Bantuan CS) -->
        <nav class="hidden md:flex items-center gap-8 text-xs font-bold text-[#010310]/80">
            <a href="{{ route('katalog') }}" class="hover:text-[#23A9BD] transition">Katalog</a>
            <a href="{{ route('support.create') }}" class="hover:text-[#23A9BD] transition">Bantuan CS</a>
        </nav>

        <!-- Menu Kanan (Reaktif terhadap Status Login) -->
        <div class="flex items-center gap-2.5">
            @guest
                <!-- Belum Login: Tampilkan Sign In & Sign Up -->
                <a href="{{ route('login') }}"
                   class="px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-transparent text-[#010310] border-[1.5px] border-[#010310] hover:bg-[#23A9BD] hover:text-white hover:border-[#010310] transition-colors duration-200">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-transparent text-[#010310] border-[1.5px] border-[#010310] hover:bg-[#23A9BD] hover:text-white hover:border-[#010310] transition-colors duration-200">
                    Sign Up
                </a>
            @else
                <!-- Sudah Login: Sambutan Pengguna & Tombol Logout -->
                <span class="hidden sm:inline text-[11px] font-bold text-[#010310]/70 mr-1">
                    Halo, {{ Auth::user()->name }}!
                </span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-[#010310] text-white border-[1.5px] border-[#010310] hover:bg-rose-600 hover:border-rose-600 cursor-pointer transition-colors duration-200">
                        Logout
                    </button>
                </form>
            @endguest
        </div>
    </header>

    <!-- 2. TEKS & TOMBOL HERO -->
    <main class="w-full max-w-3xl mx-auto px-6 pt-3 text-center relative z-10 flex flex-col items-center bg-transparent">
        <!-- Badge Atas -->
        <div class="gsap-hero-item inline-block px-3 py-0.5 rounded-full border border-[#010310] bg-transparent text-[11px] font-medium text-[#010310] mb-3">
            Comifuro & Convention Batch #1 Open
        </div>

        <!-- Headline Utama -->
        <h1 class="gsap-hero-item font-nohemi font-bold text-5xl sm:text-6xl lg:text-[64px] tracking-[-0.04em] [word-spacing:-0.06em] text-[#010310] leading-[0.96] mb-3">
            Ready for your <br class="hidden sm:inline"> next <span class="text-[#23A9BD] underline decoration-[3px] underline-offset-4">loot(box)?</span>
        </h1>

        <!-- Subtitle / Deskripsi -->
        <p class="gsap-hero-item font-jakarta max-w-sm mx-auto text-[11px] sm:text-xs text-[#010310]/65 font-medium tracking-[-0.01em] leading-relaxed mb-5">
            Amankan standee akrilik, gantungan kunci, dan art print anime favoritmu sebelum kehabisan slot produksi. Ambil langsung di booth pameran tanpa antre!
        </p>

        <!-- Single CTA Button (Mengarah ke Login Dulu, Jika Sudah Login Lanjut ke Katalog Pre-Order) -->
        <div class="gsap-hero-item flex items-center justify-center">
            <a href="{{ auth()->check() ? route('katalog') : route('login') }}"
               class="px-5 py-2 rounded-full bg-[#23A9BD] text-white font-bold text-xs border-[1.5px] border-[#010310] shadow-[2.5px_2.5px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_#010310] active:translate-x-[2.5px] active:translate-y-[2.5px] active:shadow-none transition-all inline-flex items-center gap-2">
                Mulai Pre-Order Sekarang
                <x-icon name="sparkle" class="w-3.5 h-3.5"/>
            </a>
        </div>
    </main>

</body>
</html>