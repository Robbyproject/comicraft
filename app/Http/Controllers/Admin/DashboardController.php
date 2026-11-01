<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoothRental;
use App\Models\ConventionEvent;
use App\Models\FandomCategory;
use App\Models\Merchandise;
use App\Models\Preorder;
use App\Models\SupportMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DashboardController extends Controller
{
    private function getSidebarData(): array
    {
        $pendingTickets = SupportMessage::with('user')->where('status', 'waiting_reply')->latest()->take(3)->get();
        $newOrders = Preorder::with(['user', 'merchandise'])->where('status', 'pending')->latest()->take(3)->get();

        return [
            'categories' => FandomCategory::with('merchandises')->get(),
            'pendingSupportCount' => SupportMessage::where('status', 'waiting_reply')->count(),
            'activeBooth' => BoothRental::with('event')
                ->where('creator_id', auth()->id())
                ->where('rental_status', 'paid_confirmed')
                ->latest()
                ->first(),
            'newOrdersCount' => $newOrders->count(),
            'recentNotifications' => [
                'tickets' => $pendingTickets,
                'orders' => $newOrders,
            ],
        ];
    }

    /**
     * Merchandise lengkap dengan sisa slot produksi dan satu hitungan
     * pre-order per status, siap dikonsumsi tiap widget dashboard.
     *
     * @return Collection<int, Merchandise>
     */
    private function merchandisesWithProductionStats(?int $categoryId = null): Collection
    {
        return Merchandise::with('category')
            ->withCount([
                'preorders as pending_orders_count' => fn (Builder $query) => $query->where('status', 'pending'),
                'preorders as producing_orders_count' => fn (Builder $query) => $query->where('status', 'producing'),
                'preorders as ready_at_booth_orders_count' => fn (Builder $query) => $query->where('status', 'ready_at_booth'),
                'preorders as completed_orders_count' => fn (Builder $query) => $query->where('status', 'completed'),
                'preorders as cancelled_orders_count' => fn (Builder $query) => $query->where('status', 'cancelled'),
            ])
            ->withSum([
                'preorders as reserved_quota' => fn (Builder $query) => $query->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA),
            ], 'quantity')
            ->when($categoryId, fn (Builder $query) => $query->where('fandom_category_id', $categoryId))
            ->latest()
            ->get();
    }

    /**
     * Simpan unggahan gambar baru ke disk public dan hapus file lama bila diganti.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function withImageUpload(Request $request, array $attributes): array
    {
        if (! $request->hasFile('image')) {
            return $attributes;
        }

        $path = $request->file('image')->store('merchandise-images', 'public');

        if ($path === false) {
            throw new RuntimeException('Failed to store merchandise image.');
        }

        $attributes['image'] = $path;

        return $attributes;
    }

    // 1. HALAMAN OVERVIEW
    public function index(Request $request)
    {
        $sidebar = $this->getSidebarData();
        $merchandises = $this->merchandisesWithProductionStats();
        $preorders = Preorder::with(['user', 'merchandise.category'])->latest()->take(6)->get();
        $supportMessages = SupportMessage::with(['user', 'preorder'])->latest()->take(4)->get();

        $totalRevenue = Preorder::where('status', '!=', 'cancelled')->sum('total_price');
        $totalOrders = Preorder::count();
        $readyAtBoothCount = Preorder::where('status', 'ready_at_booth')->count();
        $completedCount = Preorder::where('status', 'completed')->count();
        $completionRate = $totalOrders > 0 ? round(($completedCount / $totalOrders) * 100) : 0;

        $scannedOrder = null;
        if ($request->filled('pickup_code')) {
            $scannedOrder = Preorder::with(['user', 'merchandise'])
                ->where('preorder_code', trim($request->pickup_code))
                ->first();
        }

        return view('admin.dashboard', array_merge($sidebar, compact(
            'merchandises',
            'preorders',
            'supportMessages',
            'totalRevenue',
            'totalOrders',
            'readyAtBoothCount',
            'completedCount',
            'completionRate',
            'scannedOrder'
        )));
    }

    // 2. HALAMAN PRE-ORDER QUEUE
    public function preorders(Request $request)
    {
        $sidebar = $this->getSidebarData();

        $query = Preorder::with(['user', 'merchandise.category'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('preorder_code', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $preorders = $query->get();

        $counts = [
            'all' => Preorder::count(),
            'pending' => Preorder::where('status', 'pending')->count(),
            'producing' => Preorder::where('status', 'producing')->count(),
            'ready_at_booth' => Preorder::where('status', 'ready_at_booth')->count(),
            'completed' => Preorder::where('status', 'completed')->count(),
        ];

        return view('admin.preorders', array_merge($sidebar, compact('preorders', 'counts')));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,producing,ready_at_booth,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'verification_pin' => 'nullable|string|size:6',
        ]);

        $preorder = Preorder::findOrFail($id);
        $newStatus = $request->status;
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'completed' && $preorder->pickup_method === 'shipping') {
            if (! $request->filled('tracking_number') && ! $preorder->tracking_number) {
                return back()->with('error', 'Wajib memasukkan Nomor Resi Ekspedisi sebelum menyelesaikan pesanan kirim!');
            }
            if ($request->filled('tracking_number')) {
                $updateData['tracking_number'] = trim($request->tracking_number);
            }
        }

        if ($newStatus === 'completed' && $preorder->pickup_method === 'booth_pickup') {
            if ($request->verification_pin !== $preorder->pickup_secret_pin) {
                return back()->with('error', 'PIN Serah-Terima salah! Minta pembeli menunjukkan PIN 6 digit dari tiketnya.');
            }
        }

        if ($newStatus === 'completed') {
            $updateData['payment_status'] = 'released_to_creator';

            if ($preorder->payment_scheme === 'down_payment') {
                $totalPrice = $preorder->total_price;
                $platformFee = (int) round($totalPrice * 0.015);
                $updateData['amount_paid'] = $totalPrice;
                $updateData['remaining_balance'] = 0;
                $updateData['platform_fee'] = $platformFee;
                $updateData['net_creator_income'] = $totalPrice - $platformFee;
            }
        } elseif ($newStatus === 'cancelled') {
            $updateData['payment_status'] = 'unpaid';
            $preorder->merchandise->increment('stock_quota', $preorder->quantity);
        }

        $preorder->update($updateData);

        return back()->with('success', "Validasi serah-terima pesanan #{$preorder->preorder_code} berhasil! Dana Escrow resmi dicairkan.");
    }

    public function destroyOrder($id)
    {
        $preorder = Preorder::findOrFail($id);
        $code = $preorder->preorder_code;
        $preorder->delete();

        return back()->with('success', "Data pesanan #{$code} berhasil dihapus.");
    }

    // 3. HALAMAN MERCH & QUOTA (CRUD MERCHANDISE + FANDOM)
    public function merchandises(Request $request)
    {
        $sidebar = $this->getSidebarData();

        $merchandises = $this->merchandisesWithProductionStats(
            $request->filled('category_id') ? (int) $request->category_id : null,
        );

        $editMerch = null;
        if ($request->filled('edit')) {
            $editMerch = Merchandise::find($request->edit);
        }

        return view('admin.merchandises', array_merge($sidebar, compact('merchandises', 'editMerch')));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:fandom_categories,name',
        ]);

        FandomCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "Kategori Fandom '{$validated['name']}' berhasil ditambahkan!");
    }

    public function storeMerchandise(Request $request)
    {
        $validated = $this->validateMerchandise($request);

        Merchandise::create($this->withImageUpload($request, $validated));

        return back()->with('success', "Merchandise '{$validated['name']}' berhasil ditambahkan ke katalog!");
    }

    public function updateMerchandise(Request $request, Merchandise $merchandise)
    {
        $validated = $this->validateMerchandise($request, 0);
        $oldImage = $merchandise->image;
        $validated = $this->withImageUpload($request, $validated);

        $merchandise->update($validated);

        if ($oldImage && $oldImage !== $merchandise->image && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.merchandises')
            ->with('success', "Merchandise '{$merchandise->name}' berhasil diperbarui.");
    }

    public function destroyMerchandise($id)
    {
        $merch = Merchandise::findOrFail($id);
        $name = $merch->name;
        $image = $merch->image;
        $merch->delete();

        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }

        return back()->with('success', "Merchandise '{$name}' berhasil dihapus.");
    }

    /**
     * Validasi payload CRUD merchandise.
     */
    private function validateMerchandise(Request $request, int $minStockQuota = 1): array
    {
        $image = $request->file('image');

        if ($image && ! $image->isValid()) {
            $temporaryDirectory = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
            $uploadSetting = ini_get_all(null, true)['upload_tmp_dir'];

            Log::warning('Merchandise image upload failed before validation.', [
                'upload_error_code' => $image->getError(),
                'upload_error_message' => $image->getErrorMessage(),
                'upload_tmp_dir' => $temporaryDirectory,
                'upload_tmp_dir_exists' => is_dir($temporaryDirectory),
                'upload_tmp_dir_writable' => is_writable($temporaryDirectory),
                'upload_tmp_dir_global' => $uploadSetting['global_value'],
                'php_ini' => php_ini_loaded_file(),
                'php_sapi' => PHP_SAPI,
                'request_host' => $request->getHost(),
                'request_path' => $request->path(),
                'process_id' => getmypid(),
            ]);
        }

        return $request->validate([
            'fandom_category_id' => 'required|exists:fandom_categories,id',
            'name' => 'required|string|max:255',
            'item_type' => 'required|in:acrylic_stand,keychain,photocard,art_print',
            'price' => 'required|integer|min:1000',
            'stock_quota' => "required|integer|min:{$minStockQuota}",
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);
    }

    // 4. HALAMAN CS SUPPORT INBOX
    public function support(Request $request)
    {
        $sidebar = $this->getSidebarData();

        $query = SupportMessage::with(['user', 'preorder.merchandise'])->latest();
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->get();

        return view('admin.support', array_merge($sidebar, compact('tickets')));
    }

    public function replySupport(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $ticket = SupportMessage::findOrFail($id);
        $ticket->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => 'resolved',
        ]);

        return back()->with('success', "Balasan CS untuk tiket '{$ticket->subject}' berhasil dikirim & ditandai Resolved!");
    }

    // 5. HALAMAN SEWA BOOTH
    public function boothRentals(Request $request)
    {
        $sidebar = $this->getSidebarData();

        $events = ConventionEvent::with(['booths.creator'])->latest()->get();
        $myRentals = BoothRental::with('event')
            ->where('creator_id', auth()->id())
            ->latest()
            ->get();

        return view('admin.booth-rentals', array_merge($sidebar, compact('events', 'myRentals')));
    }

    public function bookBooth(Request $request, $id)
    {
        $validated = $request->validate([
            'circle_name' => 'required|string|max:100',
            'pic_name' => 'required|string|max:100',
            'whatsapp_number' => 'required|string|max:20',
            'merchandise_plan' => 'required|string|max:255',
            'payment_method' => 'required|in:qris_virtual,bank_transfer',
        ]);

        $booth = BoothRental::where('rental_status', 'available')->findOrFail($id);

        $invoiceNumber = 'INV-BTH-'.date('Ym').'-'.strtoupper(Str::random(4));

        $booth->update([
            'invoice_number' => $invoiceNumber,
            'creator_id' => auth()->id(),
            'circle_name' => $validated['circle_name'],
            'pic_name' => $validated['pic_name'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'merchandise_plan' => $validated['merchandise_plan'],
            'payment_method' => $validated['payment_method'],
            'rental_status' => 'paid_confirmed',
            'paid_at' => now(),
            'notes' => 'Penyewaan terverifikasi lunas via platform ComiCraft',
        ]);

        return back()->with('success', "Pembayaran lunas! Invoice #{$invoiceNumber} resmi terbit untuk Booth #{$booth->booth_number} ({$validated['circle_name']}).");
    }

    // 6. RIWAYAT TRANSAKSI & BUKU KAS FINANSIAL
    public function transactions(Request $request)
    {
        $sidebar = $this->getSidebarData();

        $boothExpenses = BoothRental::with('event')
            ->where('creator_id', auth()->id())
            ->where('rental_status', 'paid_confirmed')
            ->latest()
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'type' => 'expense',
                    'category' => 'Sewa Booth Event',
                    'reference' => $b->invoice_number ?? 'INV-BTH-'.$b->id,
                    'title' => "Sewa Booth #{$b->booth_number} - {$b->event->name}",
                    'subtitle' => "Circle: {$b->circle_name} • Fee 5%: Rp ".number_format($b->platform_fee, 0, ',', '.'),
                    'amount' => $b->total_rental_cost,
                    'status' => 'PAID',
                    'date' => $b->paid_at ?? $b->created_at,
                ];
            });

        $merchIncomes = Preorder::with('merchandise')
            ->where('payment_status', 'released_to_creator')
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'type' => 'income',
                    'category' => 'Penjualan Pre-Order',
                    'reference' => $p->preorder_code,
                    'title' => "PO {$p->merchandise->name} ({$p->quantity}x)",
                    'subtitle' => "Buyer: {$p->user->name} • Pajak 1.5%: Rp ".number_format($p->platform_fee, 0, ',', '.'),
                    'amount' => $p->net_creator_income,
                    'status' => 'RELEASED',
                    'date' => $p->updated_at,
                ];
            });

        $allTransactions = $boothExpenses->concat($merchIncomes)->sortByDesc('date');

        $totalBoothExpense = $boothExpenses->sum('amount');
        $totalMerchIncome = $merchIncomes->sum('amount');
        $netCashflow = $totalMerchIncome - $totalBoothExpense;

        return view('admin.transactions', array_merge($sidebar, compact(
            'allTransactions',
            'totalBoothExpense',
            'totalMerchIncome',
            'netCashflow'
        )));
    }
}
