<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="buyer-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen min-h-dvh flex flex-col selection:bg-[#23A9BD] selection:text-white">

    <!-- Navbar Buyer -->
    @include('buyer.partials.navbar')

    <main class="w-full max-w-4xl mx-auto px-6 py-6 flex-1 flex flex-col gap-6">

        <!-- Header Profil -->
        <div class="gsap-buyer-item mb-8 pb-4 border-b border-[#010310]/15 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded bg-[#010310] text-[#23A9BD] tracking-wider">
                    Akun Terverifikasi
                </span>
                <h1 class="font-nohemi font-black text-3xl text-[#010310] mt-2 tracking-tight">
                    Pengaturan Profil & Akun
                </h1>
                <p class="text-xs text-[#010310]/60 mt-1">
                    Kelola data kontak WhatsApp untuk notifikasi pick-up di booth convention dan ganti kata sandi.
                </p>
            </div>

            <a href="{{ route('katalog') }}" class="px-4 py-2 rounded-xl bg-white border border-[#010310] text-xs font-bold shadow-[2px_2px_0px_#010310] hover:bg-slate-50 transition">
                ← Ke Katalog
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border-2 border-emerald-500 text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-[2px_2px_0px_#010310]">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border-2 border-rose-500 text-rose-800 text-xs font-bold shadow-[2px_2px_0px_#010310]">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- KARTU INFORMASI AKUN (KIRI) -->
            <div class="md:col-span-1">
                <div class="gsap-buyer-item bg-white border-2 border-[#010310] rounded-2xl p-6 shadow-[4px_4px_0px_#010310] flex flex-col items-center text-center">
                    
                    <!-- Avatar Preview -->
                    <div class="w-24 h-24 rounded-full border-3 border-[#010310] shadow-[3px_3px_0px_#23A9BD] overflow-hidden mb-4 bg-slate-100 flex items-center justify-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-nohemi font-black text-3xl text-[#23A9BD]">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <h2 class="font-nohemi font-bold text-lg text-[#010310] leading-tight">{{ $user->name }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>

                    <div class="mt-3">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->role === 'creator' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
                            Role: {{ strtoupper($user->role) }}
                        </span>
                    </div>

                    <div class="w-full mt-6 pt-6 border-t border-gray-100 text-left text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nomor WA:</span>
                            <span class="font-bold text-[#010310]">{{ $user->phone_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Bergabung:</span>
                            <span class="font-bold text-[#010310]">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    @if($user->role === 'creator')
                        <div class="w-full mt-6">
                            <a href="{{ route('admin.dashboard') }}" class="w-full py-2.5 bg-[#010310] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 shadow-[2px_2px_0px_#23A9BD]">
                                ⚡ Buka Dashboard Kreator
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            <!-- FORM EDIT PROFIL (KANAN) -->
            <div class="md:col-span-2">
                <div class="gsap-buyer-item bg-white border-2 border-[#010310] rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#010310]">
                    <h3 class="font-nohemi font-bold text-base text-[#010310] mb-4 pb-2 border-b border-gray-100">
                        Edit Informasi Data Diri
                    </h3>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-[#010310] uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border-2 border-[#010310] text-xs font-semibold focus:outline-none focus:border-[#23A9BD]">
                        </div>

                        <!-- Email (Readonly) -->
                        <div>
                            <label class="block text-xs font-bold text-[#010310] uppercase mb-1">Alamat Email (Akun)</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 bg-gray-50 text-gray-400 text-xs font-semibold cursor-not-allowed">
                            <span class="text-[10px] text-gray-400">Email digunakan sebagai ID unik akun dan tidak dapat diubah.</span>
                        </div>

                        <!-- Nomor WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-[#010310] uppercase mb-1">Nomor WhatsApp Aktif</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-2.5 rounded-xl border-2 border-[#010310] text-xs font-semibold focus:outline-none focus:border-[#23A9BD]">
                            <span class="text-[10px] text-gray-500">Nomor ini dipakai kru booth ComiCraft untuk memverifikasi pengambilan pre-order via PIN 6-digit.</span>
                        </div>

                        <!-- Upload Foto Avatar -->
                        <div>
                            <label class="block text-xs font-bold text-[#010310] uppercase mb-1">Ganti Foto Profil (Avatar)</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-2 file:border-[#010310] file:text-xs file:font-bold file:bg-[#23A9BD] file:text-white file:shadow-[2px_2px_0px_#010310] file:cursor-pointer">
                            <span class="text-[10px] text-gray-400">Maksimal 2MB (JPG, PNG, atau WebP).</span>
                        </div>

                        <hr class="my-6 border-gray-100">

                        <h4 class="font-nohemi font-bold text-xs text-[#010310] uppercase tracking-wider text-rose-600">
                            Ganti Password (Kosongkan jika tidak ingin mengubah)
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#010310] mb-1">Password Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter"
                                       class="w-full px-4 py-2.5 rounded-xl border-2 border-[#010310] text-xs font-semibold focus:outline-none focus:border-[#23A9BD]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#010310] mb-1">Ulangi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                       class="w-full px-4 py-2.5 rounded-xl border-2 border-[#010310] text-xs font-semibold focus:outline-none focus:border-[#23A9BD]">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-3 bg-[#23A9BD] text-white text-xs font-black uppercase tracking-wider rounded-xl border-2 border-[#010310] shadow-[3px_3px_0px_#010310] hover:bg-[#1e91a3] transition active:translate-y-0.5">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </main>

</body>
</html>