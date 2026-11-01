<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS Support Inbox - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#010310] font-jakarta h-screen h-dvh overflow-hidden flex flex-col lg:flex-row selection:bg-[#23A9BD] selection:text-white">

    <!-- Sidebar Kiri Tetap -->
    @include('admin.partials.sidebar')

    <!-- Area Konten Kanan -->
    <div class="flex-1 h-screen h-dvh overflow-y-auto no-scrollbar flex flex-col p-3 sm:p-4 lg:px-6 lg:py-4 bg-white min-w-0">

        @include('admin.partials.navbar')

        <main class="flex-1 bg-[#F8F9FA] border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[3px_3px_0px_#010310] flex flex-col gap-3.5">

            <!-- Header & Filter Status Tiket -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="font-nohemi font-bold text-lg sm:text-xl tracking-tight text-[#010310] leading-tight">
                        CS Support &amp; Issue Inbox
                    </h2>
                    <p class="text-[11px] text-[#010310]/55 font-medium">
                        Tangani pertanyaan dan kendala pengambilan barang di booth dari para pembeli
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.support', ['status' => 'all']) }}"
                       class="px-3 py-1 rounded-full text-[11px] font-bold border border-[#010310] {{ request('status', 'all') === 'all' ? 'bg-[#010310] text-white' : 'bg-white text-[#010310]' }} inline-flex items-center gap-1.5">
                        <x-icon name="clipboard-list" class="w-3 h-3"/> Semua Tiket
                    </a>
                    <a href="{{ route('admin.support', ['status' => 'waiting_reply']) }}"
                       class="px-3 py-1 rounded-full text-[11px] font-bold border border-[#010310] {{ request('status') === 'waiting_reply' ? 'bg-[#23A9BD] text-white' : 'bg-white text-[#010310]' }} inline-flex items-center gap-1.5">
                        <x-icon name="clock" class="w-3 h-3"/> Menunggu Balasan ({{ $pendingSupportCount }})
                    </a>
                    <a href="{{ route('admin.support', ['status' => 'resolved']) }}"
                       class="px-3 py-1 rounded-full text-[11px] font-bold border border-[#010310] {{ request('status') === 'resolved' ? 'bg-emerald-500 text-white' : 'bg-white text-[#010310]' }} inline-flex items-center gap-1.5">
                        <x-icon name="check" class="w-3 h-3"/> Resolved
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="px-3.5 py-2 rounded-xl border border-[#010310] bg-emerald-100 text-[#010310] text-[11px] font-bold shadow-[2px_2px_0px_#010310] flex items-center gap-1.5">
                    <x-icon name="check" class="w-3 h-3 flex-shrink-0"/> {{ session('success') }}
                </div>
            @endif

            <!-- Grid Kartu Tiket CS Support -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                @forelse($tickets as $ticket)
                    <div class="bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310] flex flex-col justify-between">
                        <div>
                            <!-- Header Tiket -->
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div>
                                    <span class="text-[10px] font-bold text-[#23A9BD] uppercase">
                                        {{ $ticket->preorder ? 'Order #' . $ticket->preorder->preorder_code : 'Pertanyaan Umum' }}
                                    </span>
                                    <h3 class="font-nohemi font-bold text-sm text-[#010310]">{{ $ticket->subject }}</h3>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase border border-[#010310] inline-flex items-center gap-1 {{ $ticket->status === 'resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    @if ($ticket->status === 'resolved')
                                        <x-icon name="check" class="w-2.5 h-2.5"/> Resolved
                                    @else
                                        <x-icon name="clock" class="w-2.5 h-2.5"/> Waiting Reply
                                    @endif
                                </span>
                            </div>

                            <!-- Isi Pesan Buyer -->
                            <div class="p-3 rounded-xl bg-[#F8F9FA] border border-[#010310]/15 text-[11px] mb-3">
                                <p class="font-bold text-[#010310] mb-0.5">{{ $ticket->user->name }} <span class="font-normal text-[#010310]/50">({{ $ticket->user->email }})</span></p>
                                <p class="text-[#010310]/80 leading-relaxed">"{{ $ticket->message }}"</p>
                            </div>
                        </div>

                        <!-- Form Balasan Admin Creator -->
                        <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <textarea name="admin_reply" rows="2" required
                                      placeholder="Tulis balasan solusi untuk buyer ini..."
                                      class="w-full p-2.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-medium">{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="px-4 py-1.5 rounded-full bg-[#23A9BD] text-white font-bold text-[10px] uppercase tracking-wider border border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer inline-flex items-center gap-1.5">
                                    @if ($ticket->status === 'resolved')
                                        <x-icon name="pencil" class="w-3 h-3"/> Perbarui Balasan
                                    @else
                                        <x-icon name="sparkle" class="w-3 h-3"/> Kirim Balasan &amp; Resolve
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full bg-white border-[1.5px] border-[#010310] rounded-2xl p-10 text-center shadow-[2.5px_2.5px_0px_#010310]">
                        <span class="inline-flex w-12 h-12 rounded-full bg-[#F8F9FA] border-[1.5px] border-[#010310] items-center justify-center mb-2">
                            <x-icon name="chat" class="w-5 h-5 text-[#010310]/60"/>
                        </span>
                        <p class="font-nohemi font-bold text-sm text-[#010310]">Kotak Masuk CS Bersih!</p>
                        <p class="text-[11px] text-[#010310]/55 mt-0.5">Belum ada tiket kendala dari pembeli saat ini.</p>
                    </div>
                @endforelse
            </div>

        </main>
    </div>

</body>
</html>