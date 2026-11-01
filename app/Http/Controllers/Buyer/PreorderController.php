<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Preorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PreorderController extends Controller
{
    /**
     * Form pre-order untuk satu merchandise di katalog.
     */
    public function create(Merchandise $merchandise): View
    {
        $merchandise->load('category')
            ->loadSum([
                'preorders as reserved_quota' => fn ($query) => $query->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA),
            ], 'quantity');

        return view('buyer.preorder-form', compact('merchandise'));
    }

    /**
     * Simpan pre-order buyer bersama kode PO unik dan total harganya.
     */
    public function store(Request $request, Merchandise $merchandise): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'pickup_method' => 'required|in:booth_pickup,shipping',
            'payment_scheme' => 'required|in:full_payment,down_payment',
            'notes' => 'nullable|string|max:500',
        ]);

        $remainingQuota = $this->remainingQuotaOf($merchandise);

        if ($validated['quantity'] > $remainingQuota) {
            return back()
                ->withErrors(['quantity' => "Slot produksi tidak cukup. Tersisa {$remainingQuota} slot."])
                ->withInput();
        }

        // Kalkulasi Finansial & Escrow
        $totalPrice = $validated['quantity'] * $merchandise->price;

        if ($validated['payment_scheme'] === 'down_payment') {
            $amountPaid = (int) round($totalPrice * 0.5); // DP 50%
            $remainingBalance = $totalPrice - $amountPaid; // Sisa dilunasi di booth
        } else {
            $amountPaid = $totalPrice; // Lunas 100%
            $remainingBalance = 0;
        }

        // Pajak platform 1.5% dari uang yang masuk
        $platformFee = (int) round($amountPaid * 0.015);
        $netCreatorIncome = $amountPaid - $platformFee;

        $preorder = Preorder::create([
            'preorder_code' => $this->generatePreorderCode(),
            'pickup_secret_pin' => str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT), // PIN 6 digit unik
            'user_id' => $request->user()->id,
            'merchandise_id' => $merchandise->id,
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'pickup_method' => $validated['pickup_method'],
            'payment_scheme' => $validated['payment_scheme'],
            'amount_paid' => $amountPaid,
            'remaining_balance' => $remainingBalance,
            'platform_fee' => $platformFee,
            'net_creator_income' => $netCreatorIncome,
            'payment_status' => 'paid_in_escrow',
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        $skemaText = $validated['payment_scheme'] === 'down_payment' ? 'DP 50%' : 'Lunas';

        return redirect()->route('buyer.orders')
            ->with('success', "Pre-Order #{$preorder->preorder_code} berhasil dibuat! Catat PIN serah-terima di bawah ini.");
    }

    /**
     * Hitung sisa slot langsung dari database agar validasi selalu mutlak.
     */
    private function remainingQuotaOf(Merchandise $merchandise): int
    {
        $bookedQuota = (int) $merchandise->preorders()
            ->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA)
            ->sum('quantity');

        return max(0, (int) $merchandise->stock_quota - $bookedQuota);
    }

    /**
     * Buat kode PO acak (mis. PO-8XK2QA) dan pastikan belum dipakai.
     */
    private function generatePreorderCode(): string
    {
        do {
            $code = 'PO-'.Str::upper(Str::random(6));
        } while (Preorder::where('preorder_code', $code)->exists());

        return $code;
    }

    /**
     * Tampilkan semua tiket pre-order milik pembeli yang login.
     */
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->preorders()
            ->with(['merchandise.category'])
            ->latest()
            ->get();

        return view('buyer.orders', compact('orders'));
    }
}
