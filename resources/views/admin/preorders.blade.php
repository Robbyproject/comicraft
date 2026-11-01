<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Order Queue - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col lg:flex-row selection:bg-[#23A9BD] selection:text-white">

    <!-- Sidebar Kiri Tetap -->
    @include('admin.partials.sidebar')

    <!-- Area Konten Kanan -->
    <div class="flex-1 h-screen h-dvh overflow-y-auto no-scrollbar flex flex-col p-3 sm:p-4 lg:px-6 lg:py-4 bg-white min-w-0">

        @include('admin.partials.navbar')

        <main class="flex-1 bg-[#F8F9FA] border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[3px_3px_0px_#010310] flex flex-col gap-3.5">

            <!-- Judul Halaman & Pencarian -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="font-nohemi font-bold text-lg sm:text-xl tracking-tight text-[#010310] leading-tight">
                        Pre-Order Queue Management
                    </h2>
                    <p class="text-[11px] text-[#010310]/55 font-medium">
                        Pantau antrean pesanan, proses cetak vendor, hingga pengambilan barang di booth
                    </p>
                </div>

                <!-- Form Cari Kode PO / Nama Buyer -->
                <form action="{{ route('admin.preorders') }}" method="GET" class="flex items-center gap-1.5">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode PO / nama buyer..."
                           class="px-3.5 py-1.5 rounded-full bg-white border border-[#010310] text-[11px] text-[#010310] outline-none focus:border-[#23A9BD] w-48 sm:w-56">
                    <button type="submit"
                            class="px-3.5 py-1.5 rounded-full bg-[#23A9BD] text-white font-bold text-[11px] border border-[#010310] shadow-[2px_2px_0px_#010310] cursor-pointer">
                        Cari
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="px-3.5 py-2 rounded-xl border border-[#010310] bg-emerald-100 text-[#010310] text-[11px] font-bold shadow-[2px_2px_0px_#010310] flex items-center gap-1.5">
                    <x-icon name="check" class="w-3 h-3 flex-shrink-0"/> {{ session('success') }}
                </div>
            @endif

            <!-- Baris Filter Bento Pills -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                @php
                    $tabs = [
                        'all' => ['label' => 'Semua Order', 'icon' => 'clipboard-list'],
                        'pending' => ['label' => 'Pending', 'icon' => 'clock'],
                        'producing' => ['label' => 'Producing', 'icon' => 'palette'],
                        'ready_at_booth' => ['label' => 'Ready at Booth', 'icon' => 'package'],
                        'completed' => ['label' => 'Completed', 'icon' => 'check'],
                    ];
                    $currentStatus = request('status', 'all');
                @endphp

                @foreach($tabs as $key => $tab)
                    <a href="{{ route('admin.preorders', ['status' => $key]) }}"
                       class="p-3 rounded-2xl border-[1.5px] border-[#010310] transition flex items-center justify-between {{ $currentStatus === $key ? 'bg-[#010310] text-white shadow-[2.5px_2.5px_0px_#23A9BD]' : 'bg-white text-[#010310] shadow-[2px_2px_0px_#010310] hover:bg-[#23A9BD]/10' }}">
                        <div>
                            <p class="text-[10px] font-semibold opacity-70">{{ $tab['label'] }}</p>
                            <p class="font-nohemi font-bold text-lg leading-tight mt-0.5">{{ $counts[$key] }}</p>
                        </div>
                        <span class="text-[#23A9BD]">
                            <x-icon :name="$tab['icon']" class="w-4 h-4"/>
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Kartu Bento Tabel Utama -->
            <section class="flex-1 bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
                <div class="overflow-x-auto no-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#010310]/15 text-[9px] font-extrabold uppercase text-[#010310]/50">
                                <th class="pb-2.5">Kode Tiket</th>
                                <th class="pb-2.5">Buyer &amp; Kontak WA</th>
                                <th class="pb-2.5">Merchandise &amp; Fandom</th>
                                <th class="pb-2.5">Qty &amp; Total</th>
                                <th class="pb-2.5">Metode</th>
                                <th class="pb-2.5 text-right">Status &amp; Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#010310]/10 text-[11px]">
                            @forelse($preorders as $order)
                                <tr class="hover:bg-[#F8F9FA] transition">
                                    <td class="py-2.5 font-nohemi font-bold text-[#23A9BD]">
                                        {{ $order->preorder_code }}
                                    </td>
                                    <td class="py-2.5">
                                        <p class="font-bold text-[#010310]">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-[#010310]/55">{{ $order->user->phone_number ?? $order->user->email }}</p>
                                    </td>
                                    <td class="py-2.5">
                                        <p class="font-bold text-[#010310]">{{ $order->merchandise->name }}</p>
                                        <span class="inline-block px-2 py-0.5 rounded-full bg-[#23A9BD]/15 text-[#23A9BD] text-[9px] font-bold">
                                            {{ $order->merchandise->category->name }}
                                        </span>
                                    </td>
                                    <td class="py-2.5">
                                        <p class="font-bold text-[#010310]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-[#010310]/55">{{ $order->quantity }} pcs</p>
                                    </td>
                                    <td class="py-2.5">
                                        @if ($order->pickup_method === 'booth_pickup')
                                            <span class="px-2.5 py-1 rounded-full border border-[#010310]/20 bg-[#F8F9FA] text-[10px] font-bold inline-flex items-center gap-1">
                                                <x-icon name="map-pin" class="w-3 h-3"/> Ambil di Booth
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full border border-[#010310]/20 bg-[#F8F9FA] text-[10px] font-bold inline-flex items-center gap-1">
                                                <x-icon name="truck" class="w-3 h-3"/> Ekspedisi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()"
                                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold border border-[#010310] bg-[#F8F9FA] text-[#010310] focus:border-[#23A9BD] outline-none cursor-pointer">
                                                    <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                                    <option value="producing" @selected($order->status === 'producing')>Producing</option>
                                                    <option value="ready_at_booth" @selected($order->status === 'ready_at_booth')>Ready at Booth</option>
                                                    <option value="completed" @selected($order->status === 'completed')>Completed</option>
                                                    <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                                                </select>
                                            </form>

                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-6 h-6 rounded-full border border-[#010310]/30 hover:bg-rose-500 hover:text-white text-[10px] flex items-center justify-center transition cursor-pointer" title="Hapus">
                                                    <x-icon name="x-mark" class="w-3 h-3"/>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-xs text-[#010310]/50 font-medium">
                                        Belum ada antrean Pre-Order pada filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

</body>
</html>