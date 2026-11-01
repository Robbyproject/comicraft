<div class="grid grid-cols-2 gap-3.5 flex-1">
    <!-- Kartu Putih: Total Transaksi & Dana Escrow yang Ditahan Sistem -->
    <div class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-3.5 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="w-6 h-6 rounded-full border border-[#010310] flex items-center justify-center text-[10px]">🛡️</span>
            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[9px] font-bold">
                Escrow Protected
            </span>
        </div>

        <div class="my-2">
            <span class="text-[10px] text-[#010310]/50 font-bold uppercase block leading-tight">Uang Masuk di Escrow:</span>
            <p class="font-nohemi font-bold text-xl xl:text-2xl text-[#010310]">
                Rp {{ number_format(\App\Models\Preorder::where('payment_status', 'paid_in_escrow')->sum('amount_paid'), 0, ',', '.') }}
            </p>
        </div>

        <div class="flex items-center justify-between text-[10px] font-bold text-[#010310]/60 pt-1 border-t border-[#010310]/10">
            <span>Total Nilai PO: Rp {{ number_format(\App\Models\Preorder::where('status', '!=', 'cancelled')->sum('total_price'), 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Kartu Hitam: Pendapatan Bersih Kreator (Sudah Cair setelah Pajak 1.5%) -->
    <div class="bg-[#010310] text-white border-[1.5px] border-[#010310] rounded-2xl p-3.5 shadow-[2.5px_2.5px_0px_#23A9BD] flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-[10px]">💰</span>
            <span class="px-2 py-0.5 rounded-full bg-[#23A9BD]/20 text-[#23A9BD] text-[9px] font-bold border border-[#23A9BD]/40">
                Pajak 1.5% Terpotong
            </span>
        </div>

        <div class="my-2">
            <span class="text-[10px] text-white/60 font-bold uppercase block leading-tight">Bersih Cair ke Creator:</span>
            <p class="font-nohemi font-bold text-xl xl:text-2xl text-[#23A9BD]">
                Rp {{ number_format(\App\Models\Preorder::where('payment_status', 'released_to_creator')->sum('net_creator_income'), 0, ',', '.') }}
            </p>
        </div>

        <div class="flex items-center justify-between text-[10px] text-white/60 pt-1 border-t border-white/15">
            <span>Barang Siap/Selesai: {{ \App\Models\Preorder::whereIn('status', ['ready_at_booth', 'completed'])->count() }} PO</span>
            <span class="text-[#23A9BD]">✓ Cair</span>
        </div>
    </div>
</div>