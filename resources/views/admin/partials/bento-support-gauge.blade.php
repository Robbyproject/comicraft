<section id="support-section" class="w-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-xs font-bold text-[#010310]">Booth Fulfillment &amp; CS</h3>
            <span class="w-6 h-6 rounded-full border border-[#010310] flex items-center justify-center text-[10px]">
                <x-icon name="arrow-up-right" class="w-3 h-3"/>
            </span>
        </div>

        <div class="flex items-center gap-3 text-[9px] font-semibold text-[#010310]/60 mb-2">
            <span class="flex items-center gap-1"><i class="w-2 h-2 rounded-full bg-[#23A9BD] inline-block"></i> Completed ({{ $completedCount }})</span>
            <span class="flex items-center gap-1"><i class="w-2 h-2 rounded-full bg-slate-200 inline-block"></i> Total ({{ $totalOrders }})</span>
        </div>

        <div class="py-2 flex flex-col items-center justify-center border-b border-[#010310]/10">
            <div class="w-28 h-14 rounded-t-full border-[8px] border-b-0 border-[#23A9BD] flex items-end justify-center pb-0.5">
                <div class="text-center">
                    <span class="font-nohemi font-bold text-lg text-[#010310] leading-none">{{ $completionRate }}%</span>
                    <span class="block text-[8px] text-[#010310]/50 font-semibold uppercase">Picked Up</span>
                </div>
            </div>
        </div>

        <div class="mt-2.5 space-y-1.5">
            <p class="text-[9px] font-extrabold uppercase tracking-wider text-[#010310]/40">
                RECENT CS SUPPORT TICKETS
            </p>
            @forelse($supportMessages as $msg)
                <div class="p-2 rounded-xl bg-[#F8F9FA] border border-[#010310]/15 text-[10px]">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-[#010310] truncate">{{ $msg->subject }}</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[8px] font-bold {{ $msg->status === 'resolved' ? 'bg-emerald-100 text-emerald-700' : 'bg-[#23A9BD]/15 text-[#23A9BD]' }}">
                            {{ $msg->status === 'resolved' ? 'Resolved' : 'Waiting' }}
                        </span>
                    </div>
                    <p class="text-[9px] text-[#010310]/60 truncate mt-0.5">{{ $msg->user->name }}: "{{ $msg->message }}"</p>
                </div>
            @empty
                <div class="p-2 rounded-xl bg-[#F8F9FA] border border-[#010310]/10 text-[10px] text-[#010310]/50 text-center flex items-center justify-center gap-1.5">
                    <x-icon name="sparkle" class="w-3 h-3"/> Tidak ada kendala CS aktif
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-2.5 px-3 py-1 rounded-full bg-[#23A9BD]/10 text-[#010310] text-[9px] font-bold flex items-center justify-between">
        <span>Active Booth Status</span>
        <span class="px-2 py-0.5 rounded-full bg-[#23A9BD] text-white">Online</span>
    </div>
</section>