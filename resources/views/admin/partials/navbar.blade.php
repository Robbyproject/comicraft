<header class="w-full flex items-center justify-between gap-4 pb-3 flex-shrink-0 relative z-30">
    <!-- Teks Sapaan -->
    <div class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-3">
        <h1 class="font-nohemi font-bold text-xl sm:text-2xl tracking-tight text-[#010310] leading-none">
            Welcome Back, <span class="text-[#23A9BD]">{{ Str::words(Auth::user()->name, 1, '') }}!</span>
        </h1>
        <p class="text-[11px] text-[#010310]/50 font-medium">
            Current summary booth operational report
        </p>
    </div>

    <!-- Ikon Lonceng & Kotak Pop-up Notifikasi -->
    <div class="relative flex items-center gap-2">
        @php
            $totalNotif = ($pendingSupportCount ?? 0) + ($newOrdersCount ?? 0);
        @endphp

        <!-- Tombol Lonceng -->
        <button type="button" id="btn-toggle-notif"
                class="w-8 h-8 rounded-full bg-[#F8F9FA] border border-[#010310] flex items-center justify-center text-xs hover:bg-[#23A9BD] hover:text-white transition relative cursor-pointer">
            🔔
            <!-- Badge Titik Merah / Angka Notifikasi -->
            @if($totalNotif > 0)
                <span id="notif-badge" class="min-w-[16px] h-4 px-1 rounded-full bg-rose-500 border border-white text-white text-[9px] font-extrabold flex items-center justify-center absolute -top-1 -right-1 shadow-xs">
                    {{ $totalNotif }}
                </span>
            @endif
        </button>

        <!-- KOTAK POPUP NOTIFIKASI (BENTO POPUP) -->
        <div id="notif-popup" class="hidden absolute right-0 top-10 w-80 sm:w-88 bg-white border-2 border-[#010310] rounded-2xl shadow-[4px_4px_0px_#010310] p-4 z-50 transition-all">
            <!-- Header Notifikasi -->
            <div class="flex items-center justify-between pb-2.5 border-b border-[#010310]/15 mb-2.5">
                <div class="flex items-center gap-2">
                    <span class="font-nohemi font-bold text-xs text-[#010310]">Notifikasi Terbaru</span>
                    <span id="notif-badge-pill" class="px-2 py-0.5 rounded-full bg-[#23A9BD] text-white text-[9px] font-extrabold">
                        {{ $totalNotif }} Baru
                    </span>
                </div>
                <button type="button" id="btn-clear-all-notif" class="text-[10px] font-bold text-rose-500 hover:underline cursor-pointer">
                    Tandai Dibaca ✓
                </button>
            </div>

            <!-- List Notifikasi -->
            <div id="notif-list-container" class="space-y-2 max-h-72 overflow-y-auto no-scrollbar">
                <!-- Notifikasi Pre-Order Baru Masuk -->
                @if(isset($recentNotifications['orders']))
                    @foreach($recentNotifications['orders'] as $order)
                        <div class="notif-item p-2.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/15 hover:border-[#23A9BD] transition flex items-start gap-2.5 relative group">
                            <span class="w-6 h-6 rounded-full bg-[#23A9BD]/20 text-[#23A9BD] border border-[#23A9BD]/40 text-xs flex items-center justify-center flex-shrink-0 mt-0.5">📦</span>
                            <div class="flex-1 min-w-0 pr-4">
                                <a href="{{ route('admin.preorders') }}" class="block text-[11px] font-bold text-[#010310] hover:text-[#23A9BD] truncate">
                                    Pre-Order Baru #{{ $order->preorder_code }}
                                </a>
                                <p class="text-[10px] text-[#010310]/60 truncate">{{ $order->user->name }} memesan {{ $order->merchandise->name }} ({{ $order->quantity }}x)</p>
                                <span class="text-[9px] text-[#23A9BD] font-bold mt-1 block">Menunggu diproses ➔</span>
                            </div>
                            <button type="button" class="btn-dismiss-item text-slate-400 hover:text-rose-500 font-bold text-xs absolute top-2 right-2 cursor-pointer" title="Tutup">✕</button>
                        </div>
                    @endforeach
                @endif

                <!-- Notifikasi Pesan CS Baru -->
                @if(isset($recentNotifications['tickets']))
                    @foreach($recentNotifications['tickets'] as $ticket)
                        <div class="notif-item p-2.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/15 hover:border-[#23A9BD] transition flex items-start gap-2.5 relative group">
                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-xs flex items-center justify-center flex-shrink-0 mt-0.5">💬</span>
                            <div class="flex-1 min-w-0 pr-4">
                                <a href="{{ route('admin.support') }}" class="block text-[11px] font-bold text-[#010310] hover:text-[#23A9BD] truncate">
                                    Tiket Bantuan: {{ $ticket->subject }}
                                </a>
                                <p class="text-[10px] text-[#010310]/60 truncate">{{ $ticket->user->name }}: "{{ Str::limit($ticket->message, 35) }}"</p>
                                <span class="text-[9px] text-amber-700 font-bold mt-1 block">Perlu balasan ➔</span>
                            </div>
                            <button type="button" class="btn-dismiss-item text-slate-400 hover:text-rose-500 font-bold text-xs absolute top-2 right-2 cursor-pointer" title="Tutup">✕</button>
                        </div>
                    @endforeach
                @endif

                <!-- Tampilan Ketika Tidak Ada Notifikasi -->
                <div id="notif-empty-state" class="{{ $totalNotif > 0 ? 'hidden' : '' }} py-6 text-center text-xs text-[#010310]/50 font-medium">
                    <p class="text-xl mb-1">🎉</p>
                    <p class="font-bold text-[#010310]">Tidak ada notifikasi baru!</p>
                    <p class="text-[10px] text-[#010310]/50 mt-0.5">Semua operasional booth berjalan lancar.</p>
                </div>
            </div>
        </div>
    </div>
</header>