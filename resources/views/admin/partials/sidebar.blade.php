<aside class="w-full lg:w-56 xl:w-60 h-auto lg:h-screen lg:h-dvh p-4 sm:p-5 flex flex-col justify-between bg-white border-b lg:border-b-0 lg:border-r border-[#010310]/10 flex-shrink-0 overflow-hidden select-none">
    <div>
        <!-- Logo ComiCraft -->
        <a href="{{ route('home') }}" class="inline-block mb-4">
            <img src="{{ asset('images/ComiCraft-Logo.png') }}"
                 alt="ComiCraft Logo"
                 class="h-7 w-auto object-contain">
        </a>

        <!-- Kartu Profil Creator -->
        <div class="mb-5 flex items-center gap-2.5 p-2 rounded-2xl bg-[#F8F9FA] border border-[#010310]">
            <div class="w-8 h-8 rounded-full bg-[#23A9BD] border border-[#010310] text-white font-nohemi font-bold text-xs flex items-center justify-center flex-shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="leading-tight min-w-0 flex-1">
                <p class="text-[11px] font-bold text-[#010310] truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-[#010310]/55 font-medium truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Menu Grup: MAIN MENU (Otomatis Highlight Halaman Aktif) -->
        <div class="mb-5">
            <p class="text-[9px] font-extrabold uppercase tracking-wider text-[#010310]/40 mb-2">
                MAIN MENU
            </p>
            <nav class="space-y-1">
                <!-- 1. Overview -->
                <a href="{{ route('admin.dashboard') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]' : 'font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]' }}">
                    <span class="flex items-center gap-2">
                        <x-icon name="package" class="w-3.5 h-3.5"/> Overview
                    </span>
                    @if(request()->routeIs('admin.dashboard')) <x-icon name="chevron-down" class="w-3 h-3"/> @endif
                </a>

                <!-- 2. Pre-Order Queue -->
                <a href="{{ route('admin.preorders') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition {{ request()->routeIs('admin.preorders') ? 'bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]' : 'font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]' }}">
                    <span class="flex items-center gap-2">
                        <x-icon name="receipt" class="w-3.5 h-3.5"/> Pre-Order Queue
                    </span>
                    @if(request()->routeIs('admin.preorders')) <x-icon name="chevron-down" class="w-3 h-3"/> @endif
                </a>

                <!-- 3. Merch & Quota -->
                <a href="{{ route('admin.merchandises') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition {{ request()->routeIs('admin.merchandises') ? 'bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]' : 'font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]' }}">
                    <span class="flex items-center gap-2">
                        <x-icon name="palette" class="w-3.5 h-3.5"/> Merch &amp; Quota
                    </span>
                    @if(request()->routeIs('admin.merchandises')) <x-icon name="chevron-down" class="w-3 h-3"/> @endif
                </a>

                <!-- 5. Sewa Booth -->
                <a href="{{ route('admin.booths') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition {{ request()->routeIs('admin.booths') ? 'bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]' : 'font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]' }}">
                    <span class="flex items-center gap-2">
                        <span>🎪</span> Sewa Booth
                    </span>
                    @if(request()->routeIs('admin.booths')) <span>▾</span> @endif
                </a>

                <!-- 6. Riwayat Transaksi & Buku Kas -->
                <a href="{{ route('admin.transactions') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition {{ request()->routeIs('admin.transactions') ? 'bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]' : 'font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]' }}">
                    <span class="flex items-center gap-2">
                        <span>💳</span> Riwayat Transaksi
                    </span>
                    @if(request()->routeIs('admin.transactions')) <span>▾</span> @endif
                </a>

                <!-- 4. CS Support Inbox -->
                <a href="{{ route('admin.support') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition {{ request()->routeIs('admin.support') ? 'bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]' : 'font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]' }}">
                    <span class="flex items-center gap-2">
                        <x-icon name="chat" class="w-3.5 h-3.5"/> CS Support Inbox
                    </span>
                    @if($pendingSupportCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full bg-[#23A9BD] text-white text-[9px] font-extrabold">
                            {{ $pendingSupportCount }}
                        </span>
                    @elseif(request()->routeIs('admin.support'))
                        <x-icon name="chevron-down" class="w-3 h-3"/>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Menu Grup: FANDOM SERIES -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <p class="text-[9px] font-extrabold uppercase tracking-wider text-[#010310]/40">
                    FANDOM SERIES
                </p>
                <a href="{{ route('admin.merchandises') }}" title="Kelola Fandom"
                   class="w-4 h-4 rounded-full bg-[#23A9BD] text-white text-[10px] font-bold flex items-center justify-center hover:scale-110 transition">+</a>
            </div>
            <div class="space-y-1">
                @foreach($categories as $cat)
                    <a href="{{ route('admin.merchandises', ['category_id' => $cat->id]) }}"
                       class="flex items-center justify-between px-3 py-1.5 rounded-full text-[11px] font-semibold text-[#010310]/75 bg-[#F8F9FA] border border-[#010310]/10 hover:border-[#23A9BD] transition">
                        <span class="truncate flex items-center gap-1">
                            <x-icon name="sparkle" class="w-2.5 h-2.5"/> {{ $cat->name }}
                        </span>
                        <span class="text-[9px] font-bold text-[#23A9BD]">{{ $cat->merchandises->count() }} item</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Card Biru (#23A9BD) Dinamis Sesuai Booth yang Disewa -->
 <div class="mt-4 bg-[#23A9BD] border-[1.5px] border-[#010310] rounded-2xl p-3.5 text-white shadow-[2.5px_2.5px_0px_#010310]">
     @if(isset($activeBooth))
         <span class="inline-block px-2 py-0.5 rounded-full bg-white text-[#010310] text-[9px] font-extrabold uppercase mb-1">
             BOOTH #{{ $activeBooth->booth_number }}
         </span>
         <h4 class="font-nohemi font-bold text-sm leading-tight mb-0.5">
             {{ $activeBooth->event->name }} Ready
         </h4>
         <p class="text-[10px] text-white/85 mb-2.5 leading-snug">
             Slot aktif terkonfirmasi di venue.
         </p>
     @else
         <span class="inline-block px-2 py-0.5 rounded-full bg-white text-[#010310] text-[9px] font-extrabold uppercase mb-1">
             NO BOOTH
         </span>
         <h4 class="font-nohemi font-bold text-sm leading-tight mb-0.5">
             Belum Sewa Booth
         </h4>
         <p class="text-[10px] text-white/85 mb-2.5 leading-snug">
             Pilih slot booth di menu Sewa Booth.
         </p>
     @endif
     <form action="{{ route('logout') }}" method="POST">
         @csrf
         <button type="submit"
                 class="w-full py-1.5 px-3 rounded-full bg-[#010310] text-white text-[10px] font-bold flex items-center justify-between hover:bg-rose-600 transition cursor-pointer">
             <span>Sign Out Account</span>
             <span>↗</span>
         </button>
     </form>
 </div>
</aside>