<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="auth-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col justify-between selection:bg-[#23A9BD] selection:text-white">

    <!-- Ilustrasi Background (fixed, tidak terikat grid/shape) -->
    <img src="{{ asset('images/rei-ayanami2.png') }}"
        alt="ComiCraft Login Illustration"
        class="hidden md:block fixed bottom-0 left-0 h-[80vh] w-auto max-w-[55vw] object-contain object-bottom select-none pointer-events-none z-0">

    <!-- Navbar Minimalis -->
    <header class="gsap-auth-item relative z-10 w-full max-w-6xl mx-auto px-6 py-4 flex items-center justify-between flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center group">
            <img src="{{ asset('images/ComiCraft-Logo.png') }}"
                 alt="ComiCraft Logo"
                 class="h-8 sm:h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
        </a>
        <a href="{{ route('home') }}"
           class="px-4 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-transparent text-[#010310] border-[1.5px] border-[#010310] hover:bg-[#23A9BD] hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5">
            <x-icon name="arrow-left" class="w-3 h-3"/> Beranda
        </a>
    </header>

    <!-- Konten Utama (Terkunci Pas 1 Layar Tanpa Scroll) -->
    <main class="relative z-10 w-full max-w-5xl mx-auto px-6 flex-1 flex items-center justify-center overflow-hidden">
        <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-center">

            <!-- Form Login Compact (kolom kanan) -->
            <div class="md:col-start-7 md:col-span-6 w-full max-w-sm mx-auto">
                <!-- Heading Nohemi Bold -->
                <div class="gsap-auth-item mb-5">
                    <h1 class="font-nohemi font-bold text-4xl lg:text-[44px] tracking-[-0.04em] [word-spacing:-0.05em] text-[#010310] leading-[0.98]">
                        Hello, <br>
                        <span class="text-[#23A9BD]">Welcome back</span>
                    </h1>
                    <p class="font-jakarta text-xs text-[#010310]/60 font-medium mt-2 leading-relaxed">
                        Masuk ke akun ComiCraft kamu untuk melanjutkan pre-order atau mengelola operasional booth.
                    </p>
                </div>

                <!-- Notifikasi Error / Sukses / Info -->
                @if(session('error'))
                    <div class="mb-3 px-3 py-2 rounded-xl border border-[#010310] bg-rose-50 text-rose-600 text-[11px] font-semibold">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-3 px-3 py-2 rounded-xl border border-[#010310] bg-emerald-50 text-emerald-700 text-[11px] font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-3 px-3 py-2 rounded-xl border border-[#010310] bg-slate-100 text-[#010310] text-[11px] font-semibold">
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Form Input dengan Guard Anti Double-Submit -->
                <form id="loginForm" action="{{ route('login.submit') }}" method="POST" class="gsap-auth-item space-y-4" onsubmit="disableSubmitButton()">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="Email address"
                               class="w-full pb-2 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        @error('email')
                            <span class="text-[10px] font-semibold text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <input type="password" name="password" required
                               placeholder="Password"
                               class="w-full pb-2 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        @error('password')
                            <span class="text-[10px] font-semibold text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Baris Remember Me & Link Register -->
                    <div class="flex items-center justify-between text-[11px] font-semibold text-[#010310]/70 pt-0.5">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                   class="w-3.5 h-3.5 rounded border-[#010310] accent-[#23A9BD]">
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('register') }}" class="text-[#23A9BD] hover:underline font-bold">
                            Buat akun baru?
                        </a>
                    </div>

                    <!-- Tombol Login Utama -->
                    <div class="pt-1">
                        <button type="submit" id="submitBtn"
                                class="w-full py-2.5 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase tracking-wider border-[1.5px] border-[#010310] shadow-[2.5px_2.5px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_#010310] active:translate-x-[2.5px] active:translate-y-[2.5px] active:shadow-none transition-all cursor-pointer inline-flex items-center justify-center gap-2">
                            <span id="btnText">Login</span>
                            <x-icon name="sparkle" id="btnIcon" class="w-3.5 h-3.5"/>
                        </button>
                    </div>
                </form>

                <!-- Link ke Register -->
                <p class="text-[11px] text-[#010310]/60 font-medium mt-4 text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-[#23A9BD] font-bold hover:underline ml-0.5">
                        Click here
                    </a>
                </p>

            </div>

        </div>
    </main>

    <div class="h-2 flex-shrink-0"></div>

    <!-- Script Guard Anti Double-Submit (Mencegah Error 419) -->
    <script>
        function disableSubmitButton() {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            if (btnText) btnText.textContent = 'Memproses...';
            if (btnIcon) btnIcon.classList.add('animate-spin');
        }
    </script>

</body>
</html>