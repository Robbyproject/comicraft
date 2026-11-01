<section id="quota-section" class="w-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-[#010310]">Merchandise Quota Matrix</span>
            <div class="flex items-center gap-1.5">
                <span class="px-2.5 py-0.5 rounded-full border border-[#010310] text-[10px] font-semibold flex items-center gap-1">
                    Batch #1 <x-icon name="chevron-down" class="w-2.5 h-2.5"/>
                </span>
                <span class="w-6 h-6 rounded-full border border-[#010310] flex items-center justify-center text-[10px]">
                    <x-icon name="arrow-up-right" class="w-3 h-3"/>
                </span>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <p class="font-nohemi font-bold text-2xl sm:text-3xl text-[#010310] leading-none">
                {{ $merchandises->sum('available_quota') }} <span class="text-xs font-jakarta font-semibold text-[#010310]/50">Slots Left</span>
            </p>
            <div class="flex items-center gap-3 text-[10px] font-semibold text-[#010310]/60">
                <span class="flex items-center gap-1"><i class="w-2 h-2 rounded-full bg-[#010310] inline-block"></i> High Stock</span>
                <span class="flex items-center gap-1"><i class="w-2 h-2 rounded-full bg-[#23A9BD] inline-block"></i> Active PO</span>
                <span class="flex items-center gap-1"><i class="w-2 h-2 rounded-full bg-slate-200 inline-block"></i> Reserved</span>
            </div>
        </div>

        <!-- Baris Dot Matrix Compact -->
        <div class="space-y-2">
            @foreach($merchandises->take(3) as $merch)
                @php
                    /**
                     * 7 slot dot matrix (B1-B7): kuota cetak, sisa slot, lalu satu dot
                     * per status pre-order. Dot kosong tampil abu saat nilainya 0.
                     */
                    $quotaDotStates = [
                        ['label' => 'Quota', 'value' => $merch->stock_quota, 'filled' => 'bg-[#010310] border-[#010310] text-white'],
                        ['label' => 'Available', 'value' => $merch->available_quota, 'filled' => 'bg-white border-[#010310] text-[#010310]'],
                        ['label' => 'Pending', 'value' => $merch->pending_orders_count, 'filled' => 'bg-[#23A9BD] border-[#010310] text-white'],
                        ['label' => 'Producing', 'value' => $merch->producing_orders_count, 'filled' => 'bg-[#23A9BD] border-[#010310] text-white'],
                        ['label' => 'Ready at Booth', 'value' => $merch->ready_at_booth_orders_count, 'filled' => 'bg-white border-[#23A9BD] text-[#23A9BD]'],
                        ['label' => 'Completed', 'value' => $merch->completed_orders_count, 'filled' => 'bg-[#F8F9FA] border-[#010310] text-[#010310]'],
                        ['label' => 'Cancelled', 'value' => $merch->cancelled_orders_count, 'filled' => 'bg-white border-rose-400 text-rose-400'],
                    ];
                @endphp
                <div class="grid grid-cols-12 items-center gap-2">
                    <div class="col-span-5 pr-1">
                        <p class="text-[11px] font-bold text-[#010310] truncate">{{ $merch->name }}</p>
                        <p class="text-[9px] text-[#23A9BD] font-bold truncate">{{ $merch->category->name }} • Rp {{ number_format($merch->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-span-7 flex items-center justify-between gap-1">
                        @foreach($quotaDotStates as $dot)
                            <div title="{{ $dot['label'] }}: {{ $dot['value'] }}"
                                 class="w-6 h-6 sm:w-7 sm:h-7 rounded-full border flex items-center justify-center text-[9px] font-bold {{ $dot['value'] > 0 ? $dot['filled'] : 'bg-[#F8F9FA] border-[#010310]/15 text-[#010310]/30' }}">
                                {{ $dot['value'] > 0 ? $dot['value'] : '' }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-12 mt-3 pt-2 border-t border-[#010310]/10 text-[9px] font-bold text-[#010310]/40">
        <div class="col-span-5">ITEM STATUS</div>
        <div class="col-span-7 flex justify-between px-2">
            <span title="Quota">Q</span>
            <span title="Available">AV</span>
            <span title="Pending">PD</span>
            <span title="Producing">PR</span>
            <span title="Ready at Booth">RD</span>
            <span title="Completed">CO</span>
            <span title="Cancelled">CA</span>
        </div>
    </div>
</section>