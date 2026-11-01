<?php

namespace App\Http\Controllers;

use App\Models\FandomCategory;
use App\Models\Merchandise;
use App\Models\Preorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    // 1. Halaman Katalog Lengkap Semua Merchandise
    public function index(Request $request)
    {
        $categories = FandomCategory::withCount('merchandises')->get();
        $query = Merchandise::with('category')->where('stock_quota', '>', 0);

        if ($request->filled('fandom')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->fandom));
        }

        $merchandises = $query->latest()->get();

        return view('catalog.index', compact('categories', 'merchandises'));
    }

    // 2. Halaman Detail Spesifik Fandom (Dengan Banner)
    public function fandom($slug)
    {
        $category = FandomCategory::with(['merchandises' => fn ($q) => $q->where('stock_quota', '>', 0)])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('catalog.fandom', compact('category'));
    }

    // 3. Proses Checkout Pre-Order (Transaksi Data)
    public function storePreorder(Request $request, $merchandiseId)
    {
        // Wajib login untuk memesan
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan Pre-Order.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'pickup_method' => 'required|in:booth_pickup,shipping',
            'notes' => 'nullable|string|max:500',
        ]);

        $merchandise = Merchandise::findOrFail($merchandiseId);

        // Validasi ketersediaan kuota slot produksi (CPMK2)
        if ($merchandise->stock_quota < $validated['quantity']) {
            return back()->with('error', "Kuota slot tidak mencukupi! Sisa slot saat ini hanya {$merchandise->stock_quota} pcs.");
        }

        // Jalankan Database Transaction agar konsistensi data terjamin (CPMK2 & CPMK3)
        $preorder = DB::transaction(function () use ($validated, $merchandise) {
            // 1. Kurangi kuota stok merchandise
            $merchandise->decrement('stock_quota', $validated['quantity']);

            // 2. Buat kode tiket unik (Contoh: BOOTH-7A9B)
            $code = 'BOOTH-'.strtoupper(Str::random(5));

            // 3. Simpan transaksi pre-order
            return Preorder::create([
                'preorder_code' => $code,
                'user_id' => Auth::id(),
                'merchandise_id' => $merchandise->id,
                'quantity' => $validated['quantity'],
                'total_price' => $merchandise->price * $validated['quantity'],
                'pickup_method' => $validated['pickup_method'],
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);
        });

        return redirect()->route('buyer.orders')->with('success', "Pre-Order berhasil! Tiket Anda #{$preorder->preorder_code} siap dipantau.");
    }
}
