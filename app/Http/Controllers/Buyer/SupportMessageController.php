<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            ->with('merchandise:id,creator_id')
            ->latest()
            ->get(['id', 'preorder_code', 'merchandise_id']);
        $creators = User::query()
            ->where('role', 'creator')
            ->orderBy('name')
            ->get(['id', 'name']);
        $selectedPreorder = $preorders->firstWhere('id', (int) $request->query('preorder_id'));
        $selectedCreatorId = $selectedPreorder?->merchandise?->creator_id;

        return view('buyer.support-form', compact('preorders', 'creators', 'selectedCreatorId'));
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
            'creator_id' => [
                'nullable',
                'required_without:preorder_id',
                'integer',
                Rule::exists('users', 'id')->where('role', 'creator'),
            ],
        ]);

        $creatorId = $validated['creator_id'] ?? null;

        if (isset($validated['preorder_id'])) {
            $preorder = $request->user()
                ->preorders()
                ->with('merchandise')
                ->findOrFail($validated['preorder_id']);
            $creatorId = $preorder->merchandise->creator_id ?? $creatorId;
        }

        if ($creatorId === null) {
            return back()
                ->withErrors(['creator_id' => 'Pilih creator tujuan untuk tiket bantuan ini.'])
                ->withInput();
        }

        $request->user()->supportMessages()->create([
            'creator_id' => $creatorId,
            'preorder_id' => $validated['preorder_id'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'waiting_reply',
        ]);

        return redirect()->route('support.create')
            ->with('success', 'Tiket CS berhasil dikirim! Admin akan membalas lewat Booth Ops.');
    }
}
