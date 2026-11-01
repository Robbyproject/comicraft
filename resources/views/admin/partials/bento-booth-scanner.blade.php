<section class="w-full bg-[#010310] text-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#23A9BD]">
    <div class="flex items-center justify-between gap-2 mb-1.5">
        <span class="inline-block px-2 py-0.5 rounded-full bg-[#23A9BD]/20 text-[#23A9BD] border border-[#23A9BD]/40 text-[9px] font-extrabold uppercase">
            🛡️ Two-Way Handshake Scanner
        </span>
    </div>
    <h3 class="font-nohemi font-bold text-base leading-tight mb-2">
        Quick Booth <span class="text-[#23A9BD]">Pickup Verifier</span>
    </h3>

    <!-- Form Cari Kode PO -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-1.5">
        <input type="text" name="pickup_code" value="{{ request('pickup_code') }}"
               placeholder="Ketik Kode Tiket (PO-XXXXXX)..."
               class="flex-1 px-3 py-1.5 rounded-full bg-white/10 border border-white/25 text-[11px] text-white placeholder:text-white/40 focus:border-[#23A9BD] outline-none font-bold">
        <button type="submit"
                class="px-3.5 py-1.5 rounded-full bg-[#23A9BD] text-white font-bold text-[11px] border border-white/20 hover:bg-[#1d8f9f] transition cursor-pointer">
            Cek Tiket ↗
        </button>
    </form>

    <!-- Hasil Scan & Input PIN Serah-Terima -->
    @if(request()->filled('pickup_code'))
        <div class="mt-2 pt-2 border-t border-white/15 text-[11px]">
            @if($scannedOrder)
                @if($scannedOrder->status === 'completed')
                    <div class="bg-emerald-500/20 border border-emerald-500/40 rounded-xl p-2.5 text-center">
                        <p class="font-bold text-emerald-400">✓ Pesanan #{{ $scannedOrder->preorder_code }} Sudah Selesai</p>
                        <p class="text-[10px] text-white/70">Dana telah dicairkan ke saldo Creator.</p>
                    </div>
                @else
                    <form action="{{ route('admin.orders.updateStatus', $scannedOrder->id) }}" method="POST" class="bg-white/10 rounded-xl p-3 space-y-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">

                        <div class="flex items-center justify-between border-b border-white/10 pb-1.5">
                            <div>
                                <p class="font-bold text-[#23A9BD]">{{ $scannedOrder->preorder_code }} — {{ $scannedOrder->user->name }}</p>
                                <p class="text-[10px] text-white/80">{{ $scannedOrder->merchandise->name }} ({{ $scannedOrder->quantity }}x)</p>
                            </div>
                            @if($scannedOrder->payment_scheme === 'down_payment')
                                <span class="px-2 py-0.5 rounded-full bg-amber-400 text-[#010310] font-black text-[9px]">
                                    Sisa Tagihan di Booth: Rp {{ number_format($scannedOrder->remaining_balance, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <!-- Kolom Verifikasi Dua Arah -->
                        @if($scannedOrder->pickup_method === 'booth_pickup')
                            <div>
                                <label class="block text-[9px] uppercase font-bold text-amber-300 mb-1">
                                    Masukkan 6 Digit PIN Serah-Terima dari HP Pembeli:
                                </label>
                                <div class="flex gap-1.5">
                                    <input type="text" name="verification_pin" required maxlength="6"
                                           placeholder="Contoh: 849201"
                                           class="w-32 px-3 py-1 rounded-lg bg-black border border-[#23A9BD] text-center text-xs font-mono font-bold tracking-widest text-white outline-none">
                                    <button type="submit" class="flex-1 py-1 px-3 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-[#010310] font-extrabold text-[10px] uppercase cursor-pointer transition">
                                        Validasi &amp; Serahkan Barang ✓
                                    </button>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block text-[9px] uppercase font-bold text-[#23A9BD] mb-1">
                                    Masukkan Nomor Resi Pengiriman Kurir:
                                </label>
                                <div class="flex gap-1.5">
                                    <input type="text" name="tracking_number" required placeholder="No. Resi JNE / J&amp;T..."
                                           class="flex-1 px-2.5 py-1 rounded-lg bg-black border border-white/30 text-xs font-mono text-white outline-none">
                                    <button type="submit" class="py-1 px-3 rounded-lg bg-emerald-500 text-[#010310] font-extrabold text-[10px] uppercase cursor-pointer">
                                        Kirim Resi ✓
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                @endif
            @else
                <p class="text-rose-400 text-[10px] font-semibold">Kode "{{ request('pickup_code') }}" tidak ditemukan.</p>
            @endif
        </div>
    @endif
</section>