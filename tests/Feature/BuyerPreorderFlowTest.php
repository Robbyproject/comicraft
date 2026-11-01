<?php

namespace Tests\Feature;

use App\Models\FandomCategory;
use App\Models\Merchandise;
use App\Models\Preorder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BuyerPreorderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_opening_preorder_form(): void
    {
        $merchandise = $this->createMerchandise();

        $this->get(route('preorder.create', $merchandise))
            ->assertRedirect(route('login'));
    }

    public function test_catalog_loads_bento_grid_with_merch_and_slots(): void
    {
        $buyer = $this->createBuyer();
        $merchandise = $this->createMerchandise();

        $this->actingAs($buyer)
            ->get(route('katalog'))
            ->assertOk()
            ->assertSee('Katalog Pre-Order Merchandise')
            ->assertSee($merchandise->name)
            ->assertSee('10 slot');
    }

    public function test_buyer_can_open_preorder_form(): void
    {
        $buyer = $this->createBuyer();
        $merchandise = $this->createMerchandise();

        $this->actingAs($buyer)
            ->get(route('preorder.create', $merchandise))
            ->assertOk()
            ->assertSee('Lengkapi Data Pre-Order');
    }

    public function test_buyer_can_store_preorder_and_total_price_is_computed(): void
    {
        $buyer = $this->createBuyer();
        $merchandise = $this->createMerchandise(price: 45000, stockQuota: 10);

        $response = $this->actingAs($buyer)->post(route('preorder.store', $merchandise), [
            'quantity' => 2,
            'pickup_method' => 'booth_pickup',
            'payment_scheme' => 'full_payment',
            'notes' => 'Packaging bubble wrap, ya.',
        ]);

        $response->assertRedirect(route('buyer.orders'));

        $preorder = Preorder::query()->sole();

        $this->assertSame(2, $preorder->quantity);
        $this->assertSame(90000, $preorder->total_price);
        $this->assertSame('pending', $preorder->status);
        $this->assertStringStartsWith('PO-', $preorder->preorder_code);
    }

    public function test_preorder_cannot_exceed_remaining_production_quota(): void
    {
        $buyer = $this->createBuyer();
        $merchandise = $this->createMerchandise(stockQuota: 10);

        // Slot 8 sudah dibooking pre-order lain yang masih aktif.
        $merchandise->preorders()->create([
            'preorder_code' => 'PO-TESTAAA',
            'user_id' => $buyer->id,
            'quantity' => 8,
            'total_price' => 8 * $merchandise->price,
            'pickup_method' => 'booth_pickup',
            'status' => 'pending',
        ]);

        $this->actingAs($buyer)
            ->post(route('preorder.store', $merchandise), [
                'quantity' => 3,
                'pickup_method' => 'shipping',
                'payment_scheme' => 'full_payment',
            ])
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('preorders', 1);
    }

    public function test_cancelled_preorder_releases_production_quota(): void
    {
        $buyer = $this->createBuyer();
        $merchandise = $this->createMerchandise(stockQuota: 10);

        $merchandise->preorders()->create([
            'preorder_code' => 'PO-TESTCCC',
            'user_id' => $buyer->id,
            'quantity' => 10,
            'total_price' => 10 * $merchandise->price,
            'pickup_method' => 'booth_pickup',
            'status' => 'cancelled',
        ]);

        $merchandise->loadSum([
            'preorders as reserved_quota' => fn ($query) => $query->whereIn('status', Merchandise::STATUSES_CONSUMING_QUOTA),
        ], 'quantity');

        $this->assertSame(10, $merchandise->available_quota);
    }

    private function createBuyer(): User
    {
        return User::create([
            'name' => 'Robby Fabian (Buyer)',
            'email' => 'buyer@comicraft.test',
            'phone_number' => '081234567890',
            'password' => Hash::make('rahasia123'),
            'role' => 'buyer',
        ]);
    }

    private function createMerchandise(int $price = 45000, int $stockQuota = 10): Merchandise
    {
        $category = FandomCategory::create([
            'name' => 'Honkai Star Rail',
            'slug' => 'honkai-star-rail',
        ]);

        return $category->merchandises()->create([
            'name' => 'Shiroko Shanhaijing Art Print A4 Hologram',
            'item_type' => 'art_print',
            'price' => $price,
            'stock_quota' => $stockQuota,
        ]);
    }
}
