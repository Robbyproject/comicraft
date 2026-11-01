<section id="orders-section" class="w-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <div>
                <h3 class="text-xs font-bold text-[#010310]">Pre-Order Operational Pipeline</h3>
                <p class="text-[10px] text-[#010310]/55 font-medium">Ubah status produksi hingga barang siap diambil di booth</p>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="px-2.5 py-0.5 rounded-full bg-[#010310] text-white text-[10px] font-bold">
                    All Orders ({{ $totalOrders }})
                </span>
                <span class="px-2.5 py-0.5 rounded-full border border-[#010310] text-[10px] font-bold">
                    Ready: {{ $readyAtBoothCount }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#010310]/15 text-[9px] font-extrabold uppercase text-[#010310]/50">
                        <th class="pb-2">Kode PO</th>
                        <th class="pb-2">Buyer</th>
                        <th class="pb-2">Merchandise</th>
                        <th class="pb-2">Total</th>
                        <th class="pb-2 text-right">Update Status Operasional</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#010310]/10 text-[11px]">
                    @forelse($preorders as $order)
                        <tr class="hover:bg-[#F8F9FA] transition">
                            <td class="py-2 font-nohemi font-bold text-[#23A9BD]">
                                {{ $order->preorder_code }}
                            </td>
                            <td class="py-2">
                                <p class="font-bold text-[#010310]">{{ $order->user->name }}</p>
                                <p class="text-[9px] text-[#010310]/50 flex items-center gap-1">
                                    @if ($order->pickup_method === 'booth_pickup')
                                        <x-icon name="map-pin" class="w-2.5 h-2.5"/> Ambil di Booth
                                    @else
                                        <x-icon name="truck" class="w-2.5 h-2.5"/> Kirim Ekspedisi
                                    @endif
                                </p>
                            </td>
                            <td class="py-2">
                                <p class="font-semibold text-[#010310]">{{ $order->merchandise->name }}</p>
                                <p class="text-[9px] text-[#010310]/50">Qty: {{ $order->quantity }} pcs</p>
                            </td>
                            <td class="py-2 font-bold text-[#010310]">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="py-2 text-right">
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-[#010310] bg-[#F8F9FA] text-[#010310] focus:border-[#23A9BD] outline-none cursor-pointer">
                                        <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                        <option value="producing" @selected($order->status === 'producing')>Producing</option>
                                        <option value="ready_at_booth" @selected($order->status === 'ready_at_booth')>Ready at Booth</option>
                                        <option value="completed" @selected($order->status === 'completed')>Completed</option>
                                        <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-[11px] text-[#010310]/50 font-medium">
                                Belum ada transaksi Pre-Order yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>