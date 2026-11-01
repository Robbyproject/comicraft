<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sewa Booth - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col lg:flex-row selection:bg-[#23A9BD] selection:text-white">

    <!-- Sidebar Kiri Tetap -->
    @include('admin.partials.sidebar')

    <!-- Area Konten Kanan -->
    <div class="flex-1 h-screen h-dvh overflow-y-auto no-scrollbar flex flex-col p-3 sm:p-4 lg:px-6 lg:py-4 bg-white min-w-0">

        @include('admin.partials.navbar')

        <main class="flex-1 bg-[#F8F9FA] border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[3px_3px_0px_#010310] flex flex-col gap-3.5 relative">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="font-nohemi font-bold text-lg sm:text-xl tracking-tight text-[#010310] leading-tight">
                        Convention Booth Rental Portal
                    </h2>
                    <p class="text-[11px] text-[#010310]/55 font-medium">
                        Pilih event convention, sewa slot denah booth, dengan simulasi QRIS &amp; pajak platform flat 5%
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-[#010310]">
                        Pajak Platform: 5% Transparan
                    </span>
                    <a href="{{ route('admin.transactions') }}"
                       class="px-3 py-1 rounded-full bg-[#010310] text-white text-[11px] font-bold shadow-[2px_2px_0px_#23A9BD] hover:bg-[#23A9BD] transition">
                        Buku Kas &amp; Invoice ↗
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="px-3.5 py-2 rounded-xl border border-[#010310] bg-emerald-100 text-[#010310] text-[11px] font-bold shadow-[2px_2px_0px_#010310]">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- BENTO 1: DAFTAR BOOTH SAYA YANG SUDAH AKTIF -->
            <section class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
                <h3 class="font-nohemi font-bold text-xs uppercase tracking-wider text-[#010310]/60 mb-2.5">
                    🎪 Slot Booth &amp; Invoice Resmi Kamu
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @forelse($myRentals as $myBooth)
                        <div class="p-3.5 rounded-xl bg-[#23A9BD]/10 border border-[#23A9BD]/40 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-nohemi font-black text-xl text-[#23A9BD]">#{{ $myBooth->booth_number }}</span>
                                    <span class="px-2 py-0.5 rounded-full bg-[#23A9BD] text-white text-[9px] font-bold uppercase">
                                        {{ $myBooth->invoice_number ?? 'PAID ✓' }}
                                    </span>
                                </div>
                                <p class="font-bold text-xs text-[#010310]">{{ $myBooth->circle_name ?? 'Circle Utama' }}</p>
                                <p class="text-[10px] text-[#010310]/60">{{ $myBooth->event->name }} ({{ $myBooth->event->venue }})</p>
                            </div>
                            <div class="mt-2.5 pt-2 border-t border-[#23A9BD]/20 flex justify-between text-[10px] font-semibold">
                                <span>{{ strtoupper(str_replace('_', ' ', $myBooth->booth_type)) }}</span>
                                <span class="font-bold text-[#010310]">Rp {{ number_format($myBooth->total_rental_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-3 text-center text-xs text-[#010310]/50 font-medium">
                            Kamu belum memiliki sewa booth yang aktif.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- BENTO 2: DAFTAR EVENT & SLOT BOOTH -->
            <div class="space-y-4">
                @foreach($events as $event)
                    <section class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-[#010310]/10 mb-3 gap-2">
                            <div>
                                <span class="px-2 py-0.5 rounded-full bg-[#010310] text-white text-[9px] font-bold uppercase mr-1.5">
                                    Open Registration
                                </span>
                                <h3 class="font-nohemi font-bold text-base text-[#010310] inline">{{ $event->name }}</h3>
                                <p class="text-[11px] text-[#010310]/60 mt-0.5">📍 {{ $event->venue }} • 📅 {{ date('d M Y', strtotime($event->event_date)) }}</p>
                            </div>
                            <span class="text-xs font-bold text-[#23A9BD]">
                                {{ $event->booths->where('rental_status', 'available')->count() }} Booth Tersedia
                            </span>
                        </div>

                        <!-- Grid Pilihan Slot Booth -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($event->booths as $booth)
                                <div class="p-3.5 rounded-xl border border-[#010310]/20 bg-[#F8F9FA] flex flex-col justify-between {{ $booth->rental_status === 'available' ? 'hover:border-[#23A9BD]' : 'opacity-60 bg-slate-100' }} transition">
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-nohemi font-bold text-lg text-[#010310]">#{{ $booth->booth_number }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase {{ $booth->rental_status === 'available' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                                {{ $booth->rental_status }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] font-semibold text-[#010310]/70 uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $booth->booth_type) }}
                                        </p>
                                    </div>

                                    <div class="mt-3 pt-2.5 border-t border-[#010310]/10 space-y-1 text-[11px]">
                                        <div class="flex justify-between text-[#010310]/60">
                                            <span>Sewa Event:</span>
                                            <span>Rp {{ number_format($booth->base_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-[#23A9BD] font-bold">
                                            <span>Platform Fee (5%):</span>
                                            <span>Rp {{ number_format($booth->platform_fee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between font-bold text-[#010310] text-xs pt-1 border-t border-[#010310]/10">
                                            <span>Total Biaya:</span>
                                            <span>Rp {{ number_format($booth->total_rental_cost, 0, ',', '.') }}</span>
                                        </div>

                                        @if($booth->rental_status === 'available')
                                            <!-- Tombol Buka Modal Menggunakan Data Attribute -->
                                            <button type="button"
                                                    class="btn-open-rental-modal w-full mt-2 py-1.5 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-[10px] uppercase tracking-wider border border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer"
                                                    data-id="{{ $booth->id }}"
                                                    data-booth="{{ $booth->booth_number }}"
                                                    data-event="{{ $event->name }}"
                                                    data-base="{{ number_format($booth->base_price, 0, ',', '.') }}"
                                                    data-fee="{{ number_format($booth->platform_fee, 0, ',', '.') }}"
                                                    data-total="{{ number_format($booth->total_rental_cost, 0, ',', '.') }}">
                                                Sewa &amp; Bayar QRIS ✦
                                            </button>
                                        @else
                                            <div class="pt-2">
                                                <span class="block w-full py-1.5 rounded-full bg-slate-200 text-slate-500 font-bold text-[10px] uppercase text-center">
                                                    {{ $booth->circle_name ? 'Disewa: ' . $booth->circle_name : 'Tersewa' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <!-- MODAL POPUP (DITARUH DI DALAM MAIN AGAR TIDAK HILANG SAAT NAVIGASI) -->
            <div id="rental-modal" class="fixed inset-0 bg-[#010310]/70 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
                <div class="bg-white border-[2px] border-[#010310] rounded-3xl max-w-md w-full p-6 shadow-[5px_5px_0px_#010310] relative">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-[#010310]/15 mb-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase text-[#23A9BD]">Official Booth Checkout</span>
                            <h3 class="font-nohemi font-bold text-lg text-[#010310]">Sewa Booth <span id="modal-booth-num"></span></h3>
                        </div>
                        <button type="button" class="btn-close-rental-modal w-7 h-7 rounded-full border border-[#010310] hover:bg-rose-500 hover:text-white font-bold text-xs flex items-center justify-center transition cursor-pointer">✕</button>
                    </div>

                    <form id="rental-form" method="POST" action="" class="space-y-3 text-xs">
                        @csrf
                        <input type="hidden" name="payment_method" value="qris_virtual">

                        <!-- STEP 1: FORM DATA CIRCLE -->
                        <div id="step-1-form" class="space-y-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Nama Circle / Brand Studio</label>
                                <input type="text" id="input-circle-name" name="circle_name" required placeholder="Contoh: Nadeshiko Art Studio"
                                       class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none font-semibold text-[11px]">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Nama PIC Penanggung Jawab</label>
                                    <input type="text" name="pic_name" value="{{ auth()->user()->name }}" required
                                           class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none font-semibold text-[11px]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">No. WhatsApp Darurat</label>
                                    <input type="text" name="whatsapp_number" value="{{ auth()->user()->phone_number }}" required placeholder="08xxxxxxxx"
                                           class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none font-semibold text-[11px]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Rencana Merchandise yang Dijual</label>
                                <input type="text" name="merchandise_plan" required placeholder="Contoh: Standee akrilik Genshin, keychain Frieren..."
                                       class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none font-semibold text-[11px]">
                            </div>

                            <div class="p-3 rounded-2xl bg-[#010310] text-white space-y-1">
                                <div class="flex justify-between text-[11px] text-white/70">
                                    <span>Biaya Sewa Event:</span>
                                    <span>Rp <span id="modal-base-price"></span></span>
                                </div>
                                <div class="flex justify-between text-[11px] text-[#23A9BD] font-bold">
                                    <span>Pajak Platform ComiCraft (5%):</span>
                                    <span>Rp <span id="modal-platform-fee"></span></span>
                                </div>
                                <div class="pt-1 border-t border-white/20 flex justify-between text-xs font-bold">
                                    <span>Total Tagihan:</span>
                                    <span class="text-[#23A9BD]">Rp <span id="modal-total-price"></span></span>
                                </div>
                            </div>

                            <button type="button" id="btn-go-to-qris"
                                    class="w-full py-2.5 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase tracking-wider border border-[#010310] shadow-[2.5px_2.5px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer mt-1 flex items-center justify-center gap-1.5">
                                <span>Lanjut ke Pembayaran QRIS</span> ➔
                            </button>
                        </div>

                        <!-- STEP 2: SIMULASI TAMPILAN QRIS RESMI DENGAN LOGO COMICRAFT -->
                        <div id="step-2-qris" class="hidden flex flex-col items-center text-center space-y-3">
                            <!-- KARTU QRIS RESMI BERGAYA STANDAR NASIONAL -->
                            <div class="w-full bg-white border-2 border-[#010310] rounded-3xl p-5 shadow-[4px_4px_0px_#010310] flex flex-col items-center relative overflow-hidden">
                                
                                <!-- Header Logo QRIS & GPN -->
                                <div class="w-full flex items-center justify-between pb-3 border-b border-[#010310]/15 mb-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-black text-xl tracking-tighter text-[#010310]">QRIS</span>
                                        <span class="text-[8px] font-bold text-white bg-[#010310] px-1.5 py-0.5 rounded uppercase tracking-wider">National</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-300 text-rose-600 text-[9px] font-black tracking-widest">
                                            GPN
                                        </span>
                                    </div>
                                </div>

                                <!-- Merchant Info -->
                                <div class="mb-3">
                                    <h4 class="font-nohemi font-bold text-sm tracking-tight text-[#010310]">
                                        COMICRAFT OFFICIAL ESCROW
                                    </h4>
                                    <p class="text-[9px] text-[#010310]/55 font-mono tracking-wider mt-0.5">
                                        NMID: ID102026889201 • A01
                                    </p>
                                </div>

                                <!-- QR Code Realistis dengan Logo ComiCraft di Tengah -->
                                <div class="relative p-3.5 bg-white border-[1.5px] border-[#010310] rounded-2xl shadow-[2px_2px_0px_#010310] flex items-center justify-center">
                                    <!-- Pola Dense Realistic SVG QR Code -->
                                    <svg class="w-48 h-48 sm:w-52 sm:h-52" viewBox="0 0 140 140" fill="#010310">
                                        <!-- Finder Top Left -->
                                        <rect x="10" y="10" width="35" height="35" rx="5" fill="#010310"/>
                                        <rect x="16" y="16" width="23" height="23" rx="2" fill="white"/>
                                        <rect x="22" y="22" width="11" height="11" rx="1.5" fill="#010310"/>

                                        <!-- Finder Top Right -->
                                        <rect x="95" y="10" width="35" height="35" rx="5" fill="#010310"/>
                                        <rect x="101" y="16" width="23" height="23" rx="2" fill="white"/>
                                        <rect x="107" y="22" width="11" height="11" rx="1.5" fill="#010310"/>

                                        <!-- Finder Bottom Left -->
                                        <rect x="10" y="95" width="35" height="35" rx="5" fill="#010310"/>
                                        <rect x="16" y="101" width="23" height="23" rx="2" fill="white"/>
                                        <rect x="22" y="107" width="11" height="11" rx="1.5" fill="#010310"/>

                                        <!-- Timing Patterns & Micro Data Points -->
                                        <g fill="#010310">
                                            <!-- Garis Horizontal / Timing -->
                                            <rect x="52" y="22" width="4" height="4"/>
                                            <rect x="62" y="22" width="4" height="4"/>
                                            <rect x="72" y="22" width="4" height="4"/>
                                            <rect x="82" y="22" width="4" height="4"/>
                                            <rect x="22" y="52" width="4" height="4"/>
                                            <rect x="22" y="62" width="4" height="4"/>
                                            <rect x="22" y="72" width="4" height="4"/>
                                            <rect x="22" y="82" width="4" height="4"/>

                                            <!-- Dense Data Matrix Kiri & Atas -->
                                            <rect x="50" y="10" width="5" height="5"/>
                                            <rect x="60" y="12" width="6" height="4"/>
                                            <rect x="75" y="10" width="5" height="7"/>
                                            <rect x="85" y="12" width="4" height="5"/>

                                            <rect x="52" y="32" width="6" height="5"/>
                                            <rect x="64" y="30" width="5" height="6"/>
                                            <rect x="74" y="33" width="7" height="4"/>
                                            <rect x="86" y="30" width="4" height="6"/>

                                            <!-- Matrix Kanan -->
                                            <rect x="95" y="50" width="6" height="5"/>
                                            <rect x="105" y="52" width="5" height="6"/>
                                            <rect x="115" y="50" width="7" height="4"/>
                                            <rect x="125" y="53" width="5" height="5"/>
                                            <rect x="98" y="62" width="8" height="5"/>
                                            <rect x="112" y="60" width="5" height="7"/>
                                            <rect x="122" y="64" width="6" height="4"/>

                                            <rect x="95" y="75" width="5" height="6"/>
                                            <rect x="104" y="72" width="6" height="5"/>
                                            <rect x="116" y="76" width="5" height="4"/>
                                            <rect x="124" y="72" width="6" height="7"/>

                                            <!-- Matrix Kiri Bawah -->
                                            <rect x="50" y="95" width="6" height="5"/>
                                            <rect x="62" y="98" width="5" height="5"/>
                                            <rect x="72" y="95" width="7" height="5"/>
                                            <rect x="84" y="97" width="5" height="6"/>

                                            <rect x="52" y="106" width="5" height="6"/>
                                            <rect x="63" y="108" width="6" height="4"/>
                                            <rect x="75" y="105" width="4" height="7"/>
                                            <rect x="85" y="108" width="6" height="5"/>

                                            <rect x="50" y="120" width="7" height="5"/>
                                            <rect x="64" y="122" width="5" height="6"/>
                                            <rect x="74" y="119" width="6" height="5"/>
                                            <rect x="86" y="122" width="5" height="5"/>

                                            <!-- Aksen Biru Chrysocolla (#23A9BD) di Sudut Data -->
                                            <rect x="10" y="52" width="6" height="6" fill="#23A9BD"/>
                                            <rect x="32" y="52" width="5" height="5" fill="#23A9BD"/>
                                            <rect x="12" y="75" width="6" height="5" fill="#23A9BD"/>
                                            <rect x="30" y="76" width="5" height="6" fill="#23A9BD"/>

                                            <rect x="98" y="95" width="6" height="6" fill="#23A9BD"/>
                                            <rect x="110" y="102" width="5" height="5" fill="#23A9BD"/>
                                            <rect x="122" y="96" width="6" height="5" fill="#23A9BD"/>
                                            <rect x="102" y="118" width="5" height="6" fill="#23A9BD"/>
                                            <rect x="118" y="120" width="7" height="5" fill="#23A9BD"/>
                                        </g>
                                    </svg>

                                    <!-- LOGO ASLI COMICRAFT DI TENGAH QR CODE -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-12 h-12 bg-white rounded-xl border-[1.5px] border-[#010310] shadow-[1.5px_1.5px_0px_#010310] flex items-center justify-center p-1.5">
                                            <img src="{{ asset('images/ComiCraft-Logo.png') }}"
                                                 alt="ComiCraft"
                                                 class="w-full h-auto object-contain">
                                        </div>
                                    </div>
                                </div>

                                <!-- Box Nominal Transaksi -->
                                <div class="w-full bg-[#F8F9FA] rounded-2xl p-3 border border-[#010310]/15 mt-3">
                                    <span class="text-[10px] text-[#010310]/60 block font-bold uppercase tracking-wider">
                                        Total Tagihan Resmi (Inc. Pajak 5%):
                                    </span>
                                    <span class="font-nohemi font-black text-2xl text-[#23A9BD] tracking-tight block mt-0.5">
                                        Rp <span id="qris-total-display"></span>
                                    </span>
                                </div>

                                <p class="text-[9px] text-[#010310]/45 font-medium mt-2">
                                    Bisa di-scan via GoPay, OVO, Dana, BCA, Mandiri, ShopeePay
                                </p>
                            </div>

                            <!-- Tombol Aksi Simulasi & Kembali -->
                            <div class="w-full space-y-2 pt-1">
                                <button type="submit"
                                        class="w-full py-3 rounded-full bg-emerald-500 hover:bg-emerald-600 text-[#010310] font-nohemi font-extrabold text-xs uppercase tracking-wider border-[1.5px] border-[#010310] shadow-[3px_3px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer flex items-center justify-center gap-2">
                                    <span>⚡ Simulasi Scan &amp; Bayar Sukses</span> ✓
                                </button>

                                <button type="button" id="btn-back-to-step1"
                                        class="w-full py-2 rounded-full bg-white text-[#010310] font-bold text-[11px] border border-[#010310]/30 hover:bg-slate-50 transition cursor-pointer">
                                    ← Ubah Rincian Data Circle
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>

</body>
</html>