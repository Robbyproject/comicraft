<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pre-Order - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="buyer-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen min-h-dvh flex flex-col selection:bg-[#23A9BD] selection:text-white">

    @include('buyer.partials.navbar')

    <main class="w-full max-w-4xl mx-auto px-6 py-6 flex-1 flex flex-col gap-4">

        @include('buyer.partials.flash')

        <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 pb-6">

            <!-- 1. Ringkasan Merchandise Sisi Kiri -->
            <section class="gsap-buyer-item md:col-span-5 bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col gap-3 h-fit">
                <div class="h-36 bg-[#F8F9FA] border-[1.5px] border-[#010310]/25 rounded-xl flex items-center justify-center overflow-hidden">
                    @if($merchandise->imageUrl())
                        <img src="{{ $merchandise->imageUrl() }}" alt="{{ $merchandise->name }}" class="w-full h-full object-cover">
                    @else
                        <x-icon :name="$merchandise->iconName()" class="w-10 h-10 text-[#010310]/30"/>
                    @endif
                </div>

                <div>
                    <h1 class="font-nohemi font-bold text-sm sm:text-base text-[#010310] leading-snug">{{ $merchandise->name }}</h1>
                    <p class="text-[10px] text-[#23A9BD] font-bold mt-0.5">
                        {{ $merchandise->category->name }} • {{ str_replace('_', ' ', $merchandise->item_type) }}
                    </p>
                </div>

                <div class="flex items-center justify-between border-t border-[#010310]/10 pt-2.5">
                    <p class="font-nohemi font-bold text-lg text-[#010310] leading-none">
                        Rp {{ number_format($merchandise->price, 0, ',', '.') }}
                    </p>
                    <span class="px-2.5 py-0.5 rounded-full bg-[#23A9BD]/15 text-[#23A9BD] text-[10px] font-bold">
                        {{ $merchandise->available_quota }} slot tersisa
                    </span>
                </div>

                <!-- Jaminan Keamanan Escrow -->
                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-500/30 text-[10px] text-emerald-800 flex items-start gap-2">
                    <span class="text-xs">🛡️</span>
                    <div>
                        <strong class="font-bold">ComiCraft Escrow Protected:</strong>
                        <p class="text-[9px] text-emerald-700 leading-tight mt-0.5">Pembayaran ditahan aman oleh sistem kami. Kreator baru bisa mencairkannya setelah kamu menerima barang di booth.</p>
                    </div>
                </div>
            </section>

            <!-- 2. Form Pre-Order Sisi Kanan -->
            <section class="gsap-buyer-item md:col-span-7 bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
                <h2 class="font-nohemi font-bold text-sm text-[#010310] mb-3 flex items-center gap-1.5">
                    <x-icon name="receipt" class="w-3.5 h-3.5"/> Lengkapi Data Pre-Order
                </h2>

                <form id="preorder-form" action="{{ route('preorder.store', $merchandise) }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf

                    <!-- Jumlah Pemesanan -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Jumlah Pemesanan</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="quantity-input" name="quantity" value="{{ old('quantity', 1) }}" required min="1"
                                   max="{{ $merchandise->available_quota }}"
                                   class="w-24 px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                            <span class="text-[10px] text-[#010310]/55 font-medium">pcs • maksimal {{ $merchandise->available_quota }} slot</span>
                        </div>
                        @error('quantity') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Skema Pembayaran: Lunas vs DP 50% -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Skema Pembayaran</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 cursor-pointer hover:border-[#23A9BD] transition">
                                <input type="radio" name="payment_scheme" value="full_payment" @checked(old('payment_scheme', 'full_payment') === 'full_payment') class="accent-[#23A9BD]" onchange="updateTotalPrice()">
                                <div>
                                    <span class="text-[11px] font-bold text-[#010310] block">Bayar Lunas (100%)</span>
                                    <span class="text-[9px] text-[#010310]/55 block leading-tight">Langsung lunas via Escrow</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 cursor-pointer hover:border-[#23A9BD] transition">
                                <input type="radio" name="payment_scheme" value="down_payment" @checked(old('payment_scheme') === 'down_payment') class="accent-[#23A9BD]" onchange="updateTotalPrice()">
                                <div>
                                    <span class="text-[11px] font-bold text-[#010310] block">Bayar DP (50%)</span>
                                    <span class="text-[9px] text-[#010310]/55 block leading-tight">Sisa 50% dilunasi di booth</span>
                                </div>
                            </label>
                        </div>
                        @error('payment_scheme') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Metode Pengambilan -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Metode Pengambilan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 px-3 py-2 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 cursor-pointer hover:border-[#23A9BD] transition">
                                <input type="radio" name="pickup_method" value="booth_pickup" @checked(old('pickup_method', 'booth_pickup') === 'booth_pickup') class="accent-[#23A9BD]">
                                <span class="text-[11px] font-semibold text-[#010310]/80 inline-flex items-center gap-1.5">
                                    <x-icon name="map-pin" class="w-3 h-3"/> Ambil di Booth
                                </span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 cursor-pointer hover:border-[#23A9BD] transition">
                                <input type="radio" name="pickup_method" value="shipping" @checked(old('pickup_method') === 'shipping') class="accent-[#23A9BD]">
                                <span class="text-[11px] font-semibold text-[#010310]/80 inline-flex items-center gap-1.5">
                                    <x-icon name="truck" class="w-3 h-3"/> Kirim Ekspedisi
                                </span>
                            </label>
                        </div>
                        @error('pickup_method') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Catatan Pesanan (opsional)</label>
                        <textarea name="notes" rows="2" maxlength="500"
                                  placeholder="Contoh: minta bubble wrap tebal..."
                                  class="w-full px-3 py-2 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-medium resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Kotak Total -->
                    <div class="bg-[#010310] text-white rounded-xl p-3.5 space-y-1.5">
                        <div class="flex items-center justify-between text-[11px] text-white/70">
                            <span>Total Nilai Pesanan:</span>
                            <span class="font-bold text-white">Rp <span id="total-price">{{ number_format($merchandise->price, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1.5 border-t border-white/15">
                            <span class="font-extrabold uppercase text-[#23A9BD]">Tagihan QRIS Sekarang:</span>
                            <p class="font-nohemi font-bold text-base text-[#23A9BD]">
                                Rp <span id="amount-now">{{ number_format($merchandise->price, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        <div id="remaining-row" class="hidden text-[10px] text-amber-300 pt-0.5 flex justify-between">
                            <span>Sisa Pelunasan di Booth:</span>
                            <span class="font-bold">Rp <span id="amount-remaining">0</span></span>
                        </div>
                    </div>

                    <!-- Tombol Membuka Modal QRIS -->
                    <button type="button" onclick="openBuyerQrisModal()"
                            class="w-full py-2.5 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase tracking-wider border-[1.5px] border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer flex items-center justify-center gap-2">
                        <span>Lanjut ke Pembayaran QRIS</span> ➔
                    </button>
                </form>
            </section>

        </div>

    </main>

    <!-- 3. MODAL POPUP SIMULASI QRIS PRE-ORDER BUYER -->
    <div id="buyer-qris-modal" class="fixed inset-0 bg-[#010310]/70 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white border-[2px] border-[#010310] rounded-3xl max-w-sm w-full p-6 shadow-[5px_5px_0px_#010310] relative">
            
            <!-- Header Modal -->
            <div class="flex items-center justify-between pb-3 border-b border-[#010310]/15 mb-3">
                <div>
                    <span class="text-[10px] font-extrabold uppercase text-[#23A9BD]">Official Pre-Order Payment</span>
                    <h3 class="font-nohemi font-bold text-base text-[#010310]">Pembayaran QRIS Escrow</h3>
                </div>
                <button type="button" onclick="closeBuyerQrisModal()" class="w-7 h-7 rounded-full border border-[#010310] hover:bg-rose-500 hover:text-white font-bold text-xs flex items-center justify-center transition cursor-pointer">✕</button>
            </div>

            <!-- Kartu QRIS -->
            <div class="w-full bg-white border-2 border-[#010310] rounded-2xl p-4 shadow-[3px_3px_0px_#010310] flex flex-col items-center text-center">
                <div class="w-full flex items-center justify-between pb-2 border-b border-[#010310]/15 mb-2">
                    <span class="font-black text-lg text-[#010310] tracking-tighter">QRIS</span>
                    <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-300 text-rose-600 text-[8px] font-black tracking-widest">
                        GPN
                    </span>
                </div>

                <p class="text-xs font-extrabold text-[#010310] uppercase">COMICRAFT PRE-ORDER ESCROW</p>
                <p class="text-[9px] text-[#010310]/50 font-mono">NMID: ID102026889201 • A01</p>

                <!-- Dense SVG QR Code dengan Logo ComiCraft di Tengah -->
                <div class="relative p-3 bg-white border-[1.5px] border-[#010310] rounded-xl shadow-[2px_2px_0px_#010310] my-2.5 flex items-center justify-center">
                    <svg class="w-40 h-40" viewBox="0 0 140 140" fill="#010310">
                        <rect x="10" y="10" width="35" height="35" rx="5" fill="#010310"/>
                        <rect x="16" y="16" width="23" height="23" rx="2" fill="white"/>
                        <rect x="22" y="22" width="11" height="11" rx="1.5" fill="#010310"/>

                        <rect x="95" y="10" width="35" height="35" rx="5" fill="#010310"/>
                        <rect x="101" y="16" width="23" height="23" rx="2" fill="white"/>
                        <rect x="107" y="22" width="11" height="11" rx="1.5" fill="#010310"/>

                        <rect x="10" y="95" width="35" height="35" rx="5" fill="#010310"/>
                        <rect x="16" y="101" width="23" height="23" rx="2" fill="white"/>
                        <rect x="22" y="107" width="11" height="11" rx="1.5" fill="#010310"/>

                        <!-- Timing & Data Matrix -->
                        <g fill="#010310">
                            <rect x="52" y="22" width="4" height="4"/><rect x="62" y="22" width="4" height="4"/><rect x="72" y="22" width="4" height="4"/><rect x="82" y="22" width="4" height="4"/>
                            <rect x="50" y="10" width="5" height="5"/><rect x="64" y="30" width="5" height="6"/><rect x="86" y="30" width="4" height="6"/>
                            <rect x="95" y="50" width="6" height="5"/><rect x="115" y="50" width="7" height="4"/><rect x="122" y="64" width="6" height="4"/>
                            <rect x="50" y="95" width="6" height="5"/><rect x="72" y="95" width="7" height="5"/><rect x="85" y="108" width="6" height="5"/>
                            <rect x="10" y="52" width="6" height="6" fill="#23A9BD"/><rect x="110" y="102" width="5" height="5" fill="#23A9BD"/>
                        </g>
                    </svg>

                    <!-- Logo ComiCraft di Tengah -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-10 h-10 bg-white rounded-lg border-[1.5px] border-[#010310] shadow-[1px_1px_0px_#010310] flex items-center justify-center p-1">
                            <img src="{{ asset('images/ComiCraft-Logo.png') }}" alt="ComiCraft" class="w-full h-auto object-contain">
                        </div>
                    </div>
                </div>

                <!-- Box Rincian Tagihan yang Dibayar -->
                <div class="w-full bg-[#F8F9FA] rounded-xl p-2.5 border border-[#010310]/15 space-y-0.5">
                    <span id="modal-scheme-label" class="text-[10px] text-[#010310]/60 block font-bold uppercase">
                        Tagihan Pembayaran Lunas (100%):
                    </span>
                    <span class="font-nohemi font-black text-xl text-[#23A9BD] block">
                        Rp <span id="modal-qris-amount">0</span>
                    </span>
                    <span class="text-[9px] text-[#010310]/50 block">Pesanan: {{ $merchandise->name }} (<span id="modal-qty-text">1</span>x)</span>
                </div>
            </div>

            <!-- Tombol Aksi Simulasi Bayar Berhasil -->
            <div class="space-y-2 mt-3.5">
                <button type="button" onclick="submitPreorderForm()"
                        class="w-full py-2.5 rounded-full bg-emerald-500 hover:bg-emerald-600 text-[#010310] font-nohemi font-extrabold text-xs uppercase tracking-wider border-[1.5px] border-[#010310] shadow-[2.5px_2.5px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer flex items-center justify-center gap-1.5">
                    <span>⚡ Simulasi Scan &amp; Bayar Sukses</span> ✓
                </button>
                <button type="button" onclick="closeBuyerQrisModal()"
                        class="w-full py-1.5 rounded-full bg-white text-[#010310] font-bold text-[11px] border border-[#010310]/30 hover:bg-slate-50 transition cursor-pointer">
                    ← Ubah Rincian Pesanan
                </button>
            </div>
        </div>
    </div>

    <!-- Script Kalkulator & Modal Handler -->
    <script>
        const quantityInput = document.getElementById('quantity-input');
        const totalPrice = document.getElementById('total-price');
        const amountNow = document.getElementById('amount-now');
        const amountRemaining = document.getElementById('amount-remaining');
        const remainingRow = document.getElementById('remaining-row');
        const itemPrice = {{ $merchandise->price }};
        const rupiahFormatter = new Intl.NumberFormat('id-ID');

        let currentPayable = itemPrice;

        const updateTotalPrice = () => {
            const quantity = Math.max(Number(quantityInput.value) || 1, 1);
            const total = itemPrice * quantity;
            const isDP = document.querySelector('input[name="payment_scheme"]:checked')?.value === 'down_payment';

            totalPrice.textContent = rupiahFormatter.format(total);

            if (isDP) {
                const dp = Math.round(total * 0.5);
                const remaining = total - dp;
                currentPayable = dp;
                amountNow.textContent = rupiahFormatter.format(dp);
                amountRemaining.textContent = rupiahFormatter.format(remaining);
                remainingRow.classList.remove('hidden');
            } else {
                currentPayable = total;
                amountNow.textContent = rupiahFormatter.format(total);
                remainingRow.classList.add('hidden');
            }
        };

        quantityInput.addEventListener('input', updateTotalPrice);
        updateTotalPrice();

        function openBuyerQrisModal() {
            const isDP = document.querySelector('input[name="payment_scheme"]:checked')?.value === 'down_payment';
            document.getElementById('modal-qris-amount').textContent = rupiahFormatter.format(currentPayable);
            document.getElementById('modal-qty-text').textContent = quantityInput.value;
            document.getElementById('modal-scheme-label').textContent = isDP ? 'Tagihan Pembayaran Uang Muka (DP 50%):' : 'Tagihan Pembayaran Lunas (100%):';
            document.getElementById('buyer-qris-modal').classList.remove('hidden');
        }

        function closeBuyerQrisModal() {
            document.getElementById('buyer-qris-modal').classList.add('hidden');
        }

        function submitPreorderForm() {
            document.getElementById('preorder-form').submit();
        }
    </script>

</body>
</html>