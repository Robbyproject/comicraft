<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportMessageController extends Controller
{
    /**
     * Form pengiriman tiket bantuan CS beserta daftar PO milik buyer.
     */
    public function create(Request $request): View
    {
        $preorders = $request->user()
            ->preorders()
            ->latest()
            ->get(['id', 'preorder_code']);

        return view('buyer.support-form', compact('preorders'));
    }

    /**
     * Simpan tiket CS buyer; status awal selalu menunggu balasan admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
            'preorder_id' => 'nullable|exists:preorders,id',
        ]);

        // Pastikan PO yang dilampirkan benar-benar milik buyer yang login.
        if (isset($validated['preorder_id'])) {
            $request->user()->preorders()->findOrFail($validated['preorder_id']);
        }

        $request->user()->supportMessages()->create([
            'preorder_id' => $validated['preorder_id'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'waiting_reply',
        ]);

        return redirect()->route('support.create')
            ->with('success', 'Tiket CS berhasil dikirim! Admin akan membalas lewat Booth Ops.');
    }
}
