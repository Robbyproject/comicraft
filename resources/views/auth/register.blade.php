<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ComiCraft</title>
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

            <!-- Form Register Compact (kolom kanan) -->
            <div class="md:col-start-7 md:col-span-6 w-full max-w-sm mx-auto">
                <!-- Heading Nohemi Bold -->
                <div class="gsap-auth-item mb-4">
                    <h1 class="font-nohemi font-bold text-4xl lg:text-[44px] tracking-[-0.04em] [word-spacing:-0.05em] text-[#010310] leading-[0.98]">
                        Join the <br>
                        <span class="text-[#23A9BD]">Alley Pass.</span>
                    </h1>
                    <p class="font-jakarta text-xs text-[#010310]/60 font-medium mt-1.5 leading-relaxed">
                        Buat akun baru sebagai Buyer atau Kreator untuk mulai memesan / menjual merchandise anime favoritmu.
                    </p>
                </div>

                <!-- Form Input -->
                <form action="{{ route('register.submit') }}" method="POST" class="gsap-auth-item space-y-3.5">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="Full name"
                               class="w-full pb-1.5 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        @error('name')
                            <span class="text-[10px] font-semibold text-rose-500 mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Email address"
                               class="w-full pb-1.5 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        @error('email')
                            <span class="text-[10px] font-semibold text-rose-500 mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required
                               placeholder="WhatsApp number (e.g. 0812xxxx)"
                               class="w-full pb-1.5 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        @error('phone_number')
                            <span class="text-[10px] font-semibold text-rose-500 mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password & Confirm Password (2 Kolom Berdampingan Agar Hemat Ruang Vertikal) -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input type="password" name="password" required
                                   placeholder="Password (min. 8)"
                                   class="w-full pb-1.5 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        </div>
                        <div>
                            <input type="password" name="password_confirmation" required
                                   placeholder="Confirm password"
                                   class="w-full pb-1.5 pt-1 bg-transparent border-b-[1.5px] border-[#010310]/25 focus:border-[#23A9BD] outline-none text-xs sm:text-sm font-medium text-[#010310] placeholder:text-[#010310]/35 transition-colors">
                        </div>
                    </div>
                    @error('password')
                        <span class="text-[10px] font-semibold text-rose-500 block">{{ $message }}</span>
                    @enderror
                    @error('role')
                        <span class="text-[10px] font-semibold text-rose-500 block">{{ $message }}</span>
                    @enderror

                    <!-- Pilihan Role (Buyer / Creator) -->
                    <div>
                        <p class="text-[10px] font-extrabold text-[#010310]/50 uppercase tracking-wider mb-1.5">
                            Daftar sebagai:
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="buyer" required
                                       class="peer sr-only" {{ old('role', 'buyer') === 'buyer' ? 'checked' : '' }}>
                                <span class="flex flex-col items-center justify-center gap-0.5 py-2.5 px-2 rounded-xl border-[1.5px] border-[#010310]/25 bg-transparent text-center transition-all
                                             peer-checked:border-[#23A9BD] peer-checked:bg-[#23A9BD]/10 peer-checked:shadow-[2px_2px_0px_#23A9BD] hover:border-[#010310]/50">
                                    <span class="text-base leading-none">🛍️</span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#010310]">Buyer</span>
                                    <span class="text-[8px] text-[#010310]/50 font-medium">Pesan merchandise</span>
                                </span>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="creator" required
                                       class="peer sr-only" {{ old('role') === 'creator' ? 'checked' : '' }}>
                                <span class="flex flex-col items-center justify-center gap-0.5 py-2.5 px-2 rounded-xl border-[1.5px] border-[#010310]/25 bg-transparent text-center transition-all
                                             peer-checked:border-[#23A9BD] peer-checked:bg-[#23A9BD]/10 peer-checked:shadow-[2px_2px_0px_#23A9BD] hover:border-[#010310]/50">
                                    <span class="text-base leading-none">🎨</span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#010310]">Creator</span>
                                    <span class="text-[8px] text-[#010310]/50 font-medium">Buka stand booth</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Sign Up -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-2.5 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase tracking-wider border-[1.5px] border-[#010310] shadow-[2.5px_2.5px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_#010310] active:translate-x-[2.5px] active:translate-y-[2.5px] active:shadow-none transition-all cursor-pointer inline-flex items-center justify-center gap-2">
                            Create Account
                            <x-icon name="sparkle" class="w-3.5 h-3.5"/>
                        </button>
                    </div>
                </form>

                <!-- Link ke Login -->
                <p class="text-[11px] text-[#010310]/60 font-medium mt-4">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#23A9BD] font-bold hover:underline ml-0.5">
                        Sign in here
                    </a>
                </p>
            </div>

        </div>
    </main>

    <div class="h-2 flex-shrink-0"></div>

</body>
</html>