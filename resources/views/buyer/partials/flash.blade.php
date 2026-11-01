@if (session('success'))
    <div class="px-3.5 py-2 rounded-xl border-[1.5px] border-[#010310] bg-emerald-100 text-[#010310] text-[11px] font-bold shadow-[2px_2px_0px_#010310] flex items-center gap-1.5">
        <x-icon name="check" class="w-3 h-3 flex-shrink-0"/> {{ session('success') }}
    </div>
@endif

@php
    $errorMessage = session('error') ?? ($errors->any() ? $errors->first() : null);
@endphp

@if ($errorMessage)
    <div class="px-3.5 py-2 rounded-xl border-[1.5px] border-[#010310] bg-rose-100 text-[#010310] text-[11px] font-bold shadow-[2px_2px_0px_#010310] flex items-center gap-1.5">
        <x-icon name="x-mark" class="w-3 h-3 flex-shrink-0"/> {{ $errorMessage }}
    </div>
@endif