<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan CS - ComiCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="buyer-body" class="bg-[#F8F9FA] text-[#010310] font-jakarta min-h-screen min-h-dvh flex flex-col selection:bg-[#23A9BD] selection:text-white">

    @include('buyer.partials.navbar')

    <main class="w-full max-w-3xl mx-auto px-6 py-6 flex-1 flex flex-col gap-4 pb-6">

        <!-- Header -->
        <section class="gsap-buyer-item bg-white border-[1.5px] border-[#010310] rounded-[24px] p-4 sm:p-5 shadow-[2.5px_2.5px_0px_#010310]">
            <h1 class="font-nohemi font-bold text-xl sm:text-2xl tracking-tight text-[#010310] leading-tight">
                Bantuan CS Booth
            </h1>
            <p class="text-[11px] sm:text-xs text-[#010310]/60 font-medium mt-1">
                Ada kendala pre-order? Kirim tiket ke admin booth dan pantau balasannya lewat notifikasi.
            </p>
        </section>

        @include('buyer.partials.flash')

        <!-- Form Tiket CS -->
        <section class="gsap-buyer-item bg-white border-[1.5px] border-[#010310] rounded-2xl p-4 shadow-[2.5px_2.5px_0px_#010310]">
            <form action="{{ route('support.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf

                <!-- Subjek -->
                <div>
                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Subjek</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="100"
                           placeholder="Contoh: Konfirmasi metode bayar PO-XXXXXX"
                           class="w-full px-3 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                    @error('subject') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                </div>

                <!-- Pre-Order Terkait -->
                <div>
                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Pre-Order Terkait (opsional)</label>
                    <select name="preorder_id"
                            class="w-full px-2.5 py-1.5 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-semibold">
                        <option value="">— Tidak terkait PO tertentu —</option>
                        @forelse($preorders as $preorder)
                            <option value="{{ $preorder->id }}" @selected(old('preorder_id') == $preorder->id)>
                                {{ $preorder->preorder_code }}
                            </option>
                        @empty
                            <option value="" disabled>Belum ada pre-order aktif</option>
                        @endforelse
                    </select>
                    @error('preorder_id') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                </div>

                <!-- Pesan -->
                <!-- Pesan -->
                <div>
                    <label class="block text-[10px] font-bold uppercase text-[#010310]/60 mb-1">Pesan</label>
                    <textarea name="message" rows="4" required maxlength="1000"
                            placeholder="Ceritakan kendala atau pertanyaan kamu..."
                            class="w-full px-3 py-2 rounded-xl bg-[#F8F9FA] border border-[#010310]/30 focus:border-[#23A9BD] outline-none text-[11px] font-medium resize-none">{{ old('message') }}</textarea>
                    @error('message') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                </div>

                <button type="submit"
                        class="w-full py-2 rounded-full bg-[#23A9BD] text-white font-nohemi font-bold text-[11px] uppercase tracking-wider border-[1.5px] border-[#010310] shadow-[2px_2px_0px_#010310] hover:translate-x-[1px] hover:translate-y-[1px] transition cursor-pointer inline-flex items-center justify-center gap-2">
                    Kirim Tiket CS
                    <x-icon name="sparkle" class="w-3 h-3"/>
                </button>
            </form>
        </section>

    </main>

</body>
</html>
