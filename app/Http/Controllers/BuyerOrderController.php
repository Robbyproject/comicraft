<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerOrderController extends Controller
{
    // Halaman Tiket Pre-Order Saya
    public function index()
    {
        $orders = Preorder::with(['merchandise.category', 'supportMessages'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $supportTickets = SupportMessage::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('buyer.orders', compact('orders', 'supportTickets'));
    }

    // Kirim Tiket Kendala / Pertanyaan ke CS Creator
    public function sendSupport(Request $request)
    {
        $validated = $request->validate([
            'preorder_id' => 'nullable|exists:preorders,id',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        SupportMessage::create([
            'user_id' => Auth::id(),
            'preorder_id' => $validated['preorder_id'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'waiting_reply',
        ]);

        return back()->with('success', 'Pesan kendala Anda berhasil dikirim ke tim Creator Booth!');
    }
}
