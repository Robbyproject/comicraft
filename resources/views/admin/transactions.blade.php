<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi &amp; Buku Kas - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col lg:flex-row selection:bg-[#23A9BD] selection:text-white">

    <!-- Sidebar Kiri Tetap -->
    @include('admin.partials.sidebar')

    <!-- Area Konten Kanan -->
    <div class="flex-1 h-screen h-dvh overflow-y-auto no-scrollbar flex flex-col p-3 sm:p-4 lg:px-6 lg:py-4 bg-white min-w-0">

        @include('admin.partials.navbar')

        <main class="flex-1 bg-[#F8F9FA] border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[3px_3px_0px_#010310] flex flex-col gap-3.5">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="font-nohemi font-bold text-lg sm:text-xl tracking-tight text-[#010310] leading-tight">
                        Financial Ledger &amp; Transaction History
                    </h2>
                    <p class="text-[11px] text-[#010310]/55 font-medium">
                        Rekapitulasi resmi pengeluaran sewa booth, pendapatan bersih pre-order, dan pajak platform
                    </p>
                </div>
                <div class="px-3 py-1 rounded-full bg-white border border-[#010310] text-[11px] font-bold">
                    Official Ledger System ✓
                </div>
            </div>

            <!-- BENTO 1: 3 KARTU RINGKASAN ARUS KAS OPERASIONAL -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                <!-- Kartu 1: Pengeluaran Sewa Booth (Keluar) -->
                <div class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
                    <span class="text-[10px] font-extrabold uppercase text-rose-600 block mb-1">
                        ↓ Total Biaya Sewa Booth
                    </span>
                    <p class="font-nohemi font-bold text-xl sm:text-2xl text-[#010310]">
                        Rp {{ number_format($totalBoothExpense, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-[#010310]/50 mt-1">Termasuk pajak platform 5%</p>
                </div>

                <!-- Kartu 2: Pemasukan Bersih Merch (Masuk) -->
                <div class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
                    <span class="text-[10px] font-extrabold uppercase text-[#23A9BD] block mb-1">
                        ↑ Pemasukan Bersih Merch
                    </span>
                    <p class="font-nohemi font-bold text-xl sm:text-2xl text-[#23A9BD]">
                        Rp {{ number_format($totalMerchIncome, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-[#010310]/50 mt-1">Sudah dipotong komisi 1.5%</p>
                </div>

                <!-- Kartu 3: Arus Kas Bersih (Net Cashflow) -->
                <div class="bg-[#010310] text-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#23A9BD]">
                    <span class="text-[10px] font-extrabold uppercase text-white/60 block mb-1">
                        Net Profit / Cashflow
                    </span>
                    <p class="font-nohemi font-bold text-xl sm:text-2xl {{ $netCashflow >= 0 ? 'text-emerald-400' : 'text-amber-400' }}">
                        Rp {{ number_format($netCashflow, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-white/50 mt-1">Selisih pemasukan vs biaya booth</p>
                </div>
            </div>

            <!-- BENTO 2: TABEL LEDGER TRANSAKSI UTUH -->
            <section class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 border-b border-[#010310]/10 pb-2.5">
                        <h3 class="text-xs font-bold text-[#010310]">Buku Kas Transaksi ({{ $allTransactions->count() }} Data)</h3>
                        <span class="text-[10px] text-[#010310]/60 font-semibold">Tercatat di Database TiDB Cloud</span>
                    </div>

                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#010310]/15 text-[9px] font-extrabold uppercase text-[#010310]/50">
                                    <th class="pb-2">Waktu &amp; Invoice / Ref</th>
                                    <th class="pb-2">Kategori</th>
                                    <th class="pb-2">Deskripsi Transaksi</th>
                                    <th class="pb-2">Arus Nominal</th>
                                    <th class="pb-2 text-right">Status Kas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#010310]/10 text-[11px]">
                                @forelse($allTransactions as $trx)
                                    <tr class="hover:bg-[#F8F9FA] transition">
                                        <td class="py-2.5">
                                            <p class="font-mono font-bold text-[#010310] text-[10px]">{{ $trx['reference'] }}</p>
                                            <p class="text-[9px] text-[#010310]/50">{{ date('d M Y, H:i', strtotime($trx['date'])) }}</p>
                                        </td>
                                        <td class="py-2.5">
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $trx['type'] === 'expense' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ $trx['category'] }}
                                            </span>
                                        </td>
                                        <td class="py-2.5">
                                            <p class="font-bold text-[#010310]">{{ $trx['title'] }}</p>
                                            <p class="text-[10px] text-[#010310]/60">{{ $trx['subtitle'] }}</p>
                                        </td>
                                        <td class="py-2.5 font-nohemi font-bold text-xs {{ $trx['type'] === 'expense' ? 'text-rose-600' : 'text-emerald-600' }}">
                                            {{ $trx['type'] === 'expense' ? '-' : '+' }} Rp {{ number_format($trx['amount'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 text-right">
                                            <span class="px-2 py-0.5 rounded-full bg-[#010310] text-white text-[9px] font-bold">
                                                ✓ {{ $trx['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-xs text-[#010310]/50 font-medium">
                                            Belum ada transaksi finansial yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>

</body>
</html>