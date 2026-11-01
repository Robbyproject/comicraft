<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pre-Order Saya - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="buyer-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen min-h-dvh flex flex-col selection:bg-[#23A9BD] selection:text-white">

    <!-- Navbar Buyer -->
    @include('buyer.partials.navbar')

    <main class="w-full max-w-5xl mx-auto px-6 py-6 flex-1 flex flex-col gap-6 pb-8">

        <!-- Header Halaman -->
        <div class="gsap-buyer-item bg-white border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[2.5px_2.5px_0px_#010310] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="font-nohemi font-bold text-xl sm:text-2xl tracking-tight text-[#010310] leading-tight">
                    Tiket Pre-Order &amp; Booth Pass
                </h1>
                <p class="text-[11px] sm:text-xs text-[#010310]/60 font-medium mt-0.5">
                    Tunjukkan PIN Serah-Terima di bawah ini ke kreator HANYA SETELAH kamu menerima fisik barangnya di booth.
                </p>
            </div>
            <a href="{{ route('katalog') }}"
               class="px-4 py-2 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase tracking-wider border border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition text-center flex-shrink-0">
                + Pesan Merch Lain ✦
            </a>
        </div>

        @include('buyer.partials.flash')

        <!-- Grid Kartu Tiket Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($orders as $order)
                <div class="gsap-buyer-card bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
                    <div>
                        <!-- Header Kartu: Kode PO & Badge Status -->
                        <div class="flex items-start justify-between border-b border-[#010310]/10 pb-3 mb-3">
                            <div>
                                <span class="text-[9px] font-extrabold uppercase text-[#010310]/40 block">Kode Pre-Order</span>
                                <p class="font-nohemi font-bold text-xl text-[#23A9BD] tracking-wider">{{ $order->preorder_code }}</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border border-[#010310]
                                {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($order->status === 'ready_at_booth' ? 'bg-[#23A9BD] text-white' : 'bg-amber-100 text-amber-800') }}">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </div>

                        <!-- Rincian Barang -->
                        <div class="space-y-1 text-xs mb-3">
                            <p class="font-bold text-sm text-[#010310]">{{ $order->merchandise->name }}</p>
                            <p class="text-[11px] text-[#010310]/60">
                                {{ $order->merchandise->category->name }} • Jumlah: <strong>{{ $order->quantity }} pcs</strong>
                            </p>
                            <p class="text-[11px] text-[#010310]/60">
                                Total Harga: <strong class="text-[#010310]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                ({{ $order->payment_scheme === 'down_payment' ? 'Skema DP 50%' : 'Skema Lunas 100%' }})
                            </p>
                        </div>

                        <!-- Blok Kotak PIN Serah-Terima Anti-Scam -->
                        <div class="bg-[#F8F9FA] border border-[#010310]/20 rounded-xl p-3 space-y-2">
                            @if($order->pickup_method === 'booth_pickup')
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-[#010310]/60 uppercase">PIN Serah-Terima di Booth:</span>
                                    <span class="font-nohemi font-black text-lg text-[#010310] tracking-widest bg-white px-2.5 py-0.5 rounded-lg border border-[#010310]">
                                        {{ $order->pickup_secret_pin ?? 'SELESAI' }}
                                    </span>
                                </div>
                                <p class="text-[9px] text-rose-600 font-semibold leading-tight">
                                    ⚠️ Berikan 6 digit PIN ini ke penjaga booth saat kamu sudah memeriksa dan memegang barangnya.
                                </p>
                            @else
                                <div>
                                    <span class="text-[10px] font-bold text-[#010310]/60 uppercase block">No. Resi Pengiriman:</span>
                                    <span class="font-mono font-bold text-xs text-[#23A9BD]">
                                        {{ $order->tracking_number ?? 'Menunggu pengiriman oleh kreator...' }}
                                    </span>
                                </div>
                            @endif

                            @if($order->payment_scheme === 'down_payment' && $order->status !== 'completed')
                                <div class="pt-2 border-t border-[#010310]/10 flex items-center justify-between text-xs">
                                    <span class="text-[10px] font-bold text-amber-800">Sisa Pelunasan di Booth:</span>
                                    <span class="font-nohemi font-bold text-amber-800">Rp {{ number_format($order->remaining_balance, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Bantuan CS untuk Tiket Ini -->
                    <div class="mt-3 pt-3 border-t border-[#010310]/10 flex items-center justify-between text-[11px]">
                        <span class="text-[#010310]/50 text-[10px]">{{ $order->pickup_method === 'booth_pickup' ? '📍 Booth #B-12' : '🚚 Ekspedisi' }}</span>
                        <a href="{{ route('support.create') }}" class="text-[#23A9BD] font-bold hover:underline">
                            Laporkan Kendala 💬
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-10 text-center shadow-[2.5px_2.5px_0px_#010310]">
                    <p class="text-3xl mb-2">🎟️</p>
                    <p class="font-nohemi font-bold text-sm text-[#010310]">Belum Ada Tiket Pre-Order</p>
                    <p class="text-[11px] text-[#010310]/55 mt-0.5 mb-4">Pesan merchandise anime favoritmu dari katalog sekarang!</p>
                    <a href="{{ route('katalog') }}" class="px-5 py-2 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-xs uppercase border border-[#010310]">
                        Buka Katalog Merchandise ✦
                    </a>
                </div>
            @endforelse
        </div>

    </main>

</body>
</html>