<nav id="buyer-navbar" class="gsap-buyer-nav w-full sticky top-0 z-50 transition-all duration-300 bg-transparent border-b-0 border-transparent">
    <div id="navbar-container" class="w-full max-w-6xl mx-auto px-6 py-4 flex items-center justify-between transition-all duration-300">

        <!-- Logo Gambar (Konsisten dengan Halaman Welcome) -->
        <a href="{{ route('katalog') }}" class="flex items-center group">
            <img src="{{ asset('images/ComiCraft-Logo.png') }}"
                 alt="ComiCraft Logo"
                 class="h-8 sm:h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
        </a>

        <!-- Menu Navigasi Tengah -->
        <nav class="hidden md:flex items-center gap-8 text-xs font-bold text-[#010310]/80">
            <a href="{{ route('katalog') }}"
               class="hover:text-[#23A9BD] transition {{ request()->routeIs('katalog') ? 'text-[#23A9BD]' : '' }}">
                Katalog
            </a>

            @auth
                @if(Auth::user()->role === 'buyer')
                    <a href="{{ route('buyer.orders') }}"
                       class="hover:text-[#23A9BD] transition {{ request()->routeIs('buyer.orders') ? 'text-[#23A9BD]' : '' }}">
                        Tiket Saya 🎫
                    </a>
                @endif

                @if(Auth::user()->role === 'creator')
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-amber-600 hover:text-amber-800 transition flex items-center gap-1">
                        ⚡ Dashboard Kreator
                    </a>
                @endif
            @endauth

            <a href="{{ route('support.create') }}"
               class="hover:text-[#23A9BD] transition {{ request()->routeIs('support.create') ? 'text-[#23A9BD]' : '' }}">
                Bantuan CS
            </a>
        </nav>

        <!-- SISI KANAN (AUTENTIKASI REAKTIF) -->
        <div class="flex items-center gap-2.5">
            @auth
                <!-- Link ke Profil dengan Avatar & Nama -->
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/90 border border-[#010310] hover:bg-slate-100 transition shadow-[1.5px_1.5px_0px_#010310]"
                   title="Buka Pengaturan Profil">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-6 h-6 rounded-full object-cover border border-[#010310]">
                    @else
                        <div class="w-6 h-6 rounded-full bg-[#23A9BD] text-white text-[10px] font-black flex items-center justify-center border border-[#010310]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-xs font-black text-[#010310] max-w-[120px] truncate">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded-md {{ Auth::user()->role === 'creator' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ Auth::user()->role }}
                    </span>
                </a>

                <!-- Tombol LOGOUT dengan Form POST Aman -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3.5 py-1.5 rounded-full bg-[#010310] text-white text-xs font-black uppercase tracking-wider hover:bg-rose-600 transition shadow-[2px_2px_0px_#23A9BD] cursor-pointer">
                        Logout
                    </button>
                </form>
            @else
                <!-- TOMBOL JIKA BELUM LOGIN (GUEST) -->
                <a href="{{ route('login') }}"
                   class="px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-transparent text-[#010310] border-[1.5px] border-[#010310] hover:bg-[#23A9BD] hover:text-white hover:border-[#010310] transition-colors duration-200">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-transparent text-[#010310] border-[1.5px] border-[#010310] hover:bg-[#23A9BD] hover:text-white hover:border-[#010310] transition-colors duration-200">
                    Sign Up
                </a>
            @endauth
        </div>

    </div>
</nav>

<!-- SCRIPT PENGHILANG GARIS SAAT FULL & MEMUNCULKAN SAAT SCROLL -->
<script>
    (() => {
        const navbar = document.getElementById('buyer-navbar');
        const container = document.getElementById('navbar-container');
        if (!navbar) return;

        const handleNavbarScroll = () => {
            if (window.scrollY > 15) {
                // Saat di-scroll ke bawah: Munculkan background putih frosted & garis border hitam
                navbar.classList.remove('bg-transparent', 'border-b-0', 'border-transparent');
                navbar.classList.add('bg-white/95', 'backdrop-blur-md', 'border-b-2', 'border-[#010310]', 'shadow-[0_4px_16px_rgba(1,3,16,0.06)]');
                if (container) {
                    container.classList.remove('py-4');
                    container.classList.add('py-2.5');
                }
            } else {
                // Saat di paling atas (posisi full): GARIS HILANG TOTAL & 100% transparan bersih
                navbar.classList.remove('bg-white/95', 'backdrop-blur-md', 'border-b-2', 'border-[#010310]', 'shadow-[0_4px_16px_rgba(1,3,16,0.06)]');
                navbar.classList.add('bg-transparent', 'border-b-0', 'border-transparent');
                if (container) {
                    container.classList.remove('py-2.5');
                    container.classList.add('py-4');
                }
            }
        };

        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
        handleNavbarScroll(); // Pastikan garis langsung hilang saat pertama kali halaman terbuka
    })();
</script>