<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creator Bento Dashboard - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col lg:flex-row selection:bg-[#23A9BD] selection:text-white">

    <!-- 1. SIDEBAR KIRI (Fixed Stay, Tidak Ikut Ter-scroll) -->
    @include('admin.partials.sidebar')

    <!-- 2. AREA KANAN (Hanya Bagian Ini yang Bisa Di-scroll + Tanpa Scrollbar Visual) -->
    <div class="flex-1 h-screen h-dvh overflow-y-auto no-scrollbar flex flex-col p-3 sm:p-4 lg:px-6 lg:py-4 bg-white min-w-0">

        <!-- NAVBAR ATAS (Berisi Teks Welcome Back) -->
        @include('admin.partials.navbar')

        <!-- MAIN BENTO CONTAINER (Ukuran Compact agar Muat & Lega di Zoom 100%) -->
        <main class="flex-1 bg-[#F8F9FA] border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[3px_3px_0px_#010310]">

            <!-- Header Sub-Judul & Filter Pills -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3.5">
                <div>
                    <h2 class="font-nohemi font-bold text-lg sm:text-xl tracking-tight text-[#010310] leading-tight">
                        Operational Overview
                    </h2>
                    <p class="text-[11px] text-[#010310]/55 font-medium">
                        Overview &gt; Comifuro &amp; Convention Batch #1 Reports
                    </p>
                </div>

                <!-- Action Pills Kanan Atas -->
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-white border border-[#010310] text-[11px] font-bold text-[#010310] flex items-center gap-1.5">
                        Batch #1 Active <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    </span>
                    <a href="{{ route('katalog') }}"
                       class="px-3 py-1 rounded-full bg-white border border-[#010310] text-[11px] font-bold text-[#010310] hover:bg-[#23A9BD] hover:text-white transition-colors inline-flex items-center gap-1.5">
                        Lihat Katalog <x-icon name="arrow-up-right" class="w-3 h-3"/>
                    </a>
                </div>
            </div>

            <!-- Notifikasi Flash Message -->
            @if(session('success'))
                <div class="mb-3.5 px-3.5 py-2 rounded-xl border border-[#010310] bg-emerald-100 text-[#010310] text-[11px] font-bold flex items-center justify-between shadow-[2px_2px_0px_#010310]">
                    <span class="flex items-center gap-1.5"><x-icon name="check" class="w-3 h-3"/> {{ session('success') }}</span>
                </div>
            @endif

            <!-- BENTO GRID BARIS 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 mb-3.5">
                <div class="lg:col-span-7 flex">
                    @include('admin.partials.bento-quota-matrix')
                </div>

                <div class="lg:col-span-5 flex flex-col gap-3.5 justify-between">
                    @include('admin.partials.bento-booth-scanner')
                    @include('admin.partials.bento-stat-cards')
                </div>
            </div>

            <!-- BENTO GRID BARIS 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5">
                <div class="lg:col-span-8 flex">
                    @include('admin.partials.bento-orders-pipeline')
                </div>

                <div class="lg:col-span-4 flex">
                    @include('admin.partials.bento-support-gauge')
                </div>
            </div>

        </main>
    </div>

</body>
</html>