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

    public function test_buyer_can_view_character_showcase_with_responsive_live2d_frame(): void
    {
        $buyer = $this->createBuyer();

        $this->actingAs($buyer)
            ->get(route('character.showcase', ['character' => 'midori']))
            ->assertOk()
            ->assertSee('Tanga Ibuki')
            ->assertSee('id="character-live2d"', false)
            ->assertSee('src="'.asset('l2d/midori/index.html').'"', false)
            ->assertSee('ResizeObserver', false)
            ->assertSee('min-height: 100svh', false)
            ->assertSee('* 1.12', false)
            ->assertSee('scrollbar-width: none', false);
    }

    public function test_tanga_ibuki_showcase_keeps_matching_existing_midori_merchandise(): void
    {
        $buyer = $this->createBuyer();
        $creator = $this->createCreator();
        $category = FandomCategory::create([
            'name' => 'Blue Archive',
            'slug' => 'blue-archive',
        ]);
        $merchandise = $creator->merchandises()->create([
            'fandom_category_id' => $category->id,
            'name' => 'Midori Saiba Art Print',
            'item_type' => 'art_print',
            'price' => 45000,
            'stock_quota' => 10,
        ]);

        $this->actingAs($buyer)
            ->get(route('character.showcase', ['character' => 'midori']))
            ->assertOk()
            ->assertSee('Tanga Ibuki')
            ->assertSee($merchandise->name);
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

    public function test_buyer_only_sees_its_own_orders_and_support_form_order_choices(): void
    {
        $buyer = $this->createBuyer();
        $otherBuyer = $this->createBuyer('other-buyer@comicraft.test');
        $merchandise = $this->createMerchandise();
        $order = $this->createPreorder($buyer, $merchandise, 'PO-BUYERONE');
        $otherOrder = $this->createPreorder($otherBuyer, $merchandise, 'PO-BUYERTWO');
        $ownTicket = $buyer->supportMessages()->create([
            'creator_id' => $merchandise->creator_id,
            'preorder_id' => $order->id,
            'subject' => 'My private ticket',
            'message' => 'Only I should see this ticket.',
            'status' => 'waiting_reply',
        ]);
        $otherTicket = $otherBuyer->supportMessages()->create([
            'creator_id' => $merchandise->creator_id,
            'preorder_id' => $otherOrder->id,
            'subject' => 'Another buyer private ticket',
            'message' => 'This ticket belongs to another buyer.',
            'status' => 'waiting_reply',
        ]);

        $this->actingAs($buyer)
            ->get(route('buyer.orders'))
            ->assertOk()
            ->assertSee($order->preorder_code)
            ->assertDontSee($otherOrder->preorder_code)
            ->assertSee('Tiket Bantuan Saya')
            ->assertSee($ownTicket->subject)
            ->assertDontSee($otherTicket->subject);

        $this->get(route('support.create'))
            ->assertOk()
            ->assertSee($order->preorder_code)
            ->assertDontSee($otherOrder->preorder_code);
    }

    public function test_buyer_support_ticket_for_a_preorder_is_routed_to_its_product_creator(): void
    {
        $buyer = $this->createBuyer();
        $creator = $this->createCreator();
        $otherCreator = $this->createCreator('other-creator@comicraft.test');
        $merchandise = $this->createMerchandise(creator: $creator);
        $order = $this->createPreorder($buyer, $merchandise, 'PO-SUPPORTONE');

        $this->actingAs($buyer)
            ->post(route('support.store'), [
                'preorder_id' => $order->id,
                'creator_id' => $otherCreator->id,
                'subject' => 'Question about my order',
                'message' => 'Please check this order.',
            ])
            ->assertRedirect(route('support.create'));

        $this->assertDatabaseHas('support_messages', [
            'user_id' => $buyer->id,
            'preorder_id' => $order->id,
            'creator_id' => $creator->id,
            'subject' => 'Question about my order',
        ]);
    }

    public function test_buyer_can_route_general_support_ticket_to_a_selected_creator(): void
    {
        $buyer = $this->createBuyer();
        $creator = $this->createCreator();

        $this->actingAs($buyer)
            ->post(route('support.store'), [
                'creator_id' => $creator->id,
                'subject' => 'General question',
                'message' => 'I have a question for this creator.',
            ])
            ->assertRedirect(route('support.create'));

        $this->assertDatabaseHas('support_messages', [
            'user_id' => $buyer->id,
            'preorder_id' => null,
            'creator_id' => $creator->id,
            'subject' => 'General question',
        ]);
    }

    public function test_buyer_cannot_route_general_support_to_another_buyer_account(): void
    {
        $buyer = $this->createBuyer();
        $otherBuyer = $this->createBuyer('other-buyer@comicraft.test');

        $this->actingAs($buyer)
            ->post(route('support.store'), [
                'creator_id' => $otherBuyer->id,
                'subject' => 'Invalid recipient',
                'message' => 'Only creator accounts can receive tickets.',
            ])
            ->assertSessionHasErrors('creator_id');

        $this->assertDatabaseMissing('support_messages', ['subject' => 'Invalid recipient']);
    }

    public function test_buyer_cannot_attach_another_buyers_preorder_to_support_ticket(): void
    {
        $buyer = $this->createBuyer();
        $otherBuyer = $this->createBuyer('other-buyer@comicraft.test');
        $creator = $this->createCreator();
        $merchandise = $this->createMerchandise(creator: $creator);
        $otherOrder = $this->createPreorder($otherBuyer, $merchandise, 'PO-OTHERBUYER');

        $this->actingAs($buyer)
            ->post(route('support.store'), [
                'preorder_id' => $otherOrder->id,
                'subject' => 'Unauthorized ticket',
                'message' => 'This ticket must not be created.',
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing('support_messages', ['subject' => 'Unauthorized ticket']);
    }

    private function createBuyer(string $email = 'buyer@comicraft.test'): User
    {
        return User::create([
            'name' => 'Robby Fabian (Buyer)',
            'email' => $email,
            'phone_number' => '081234567890',
            'password' => Hash::make('rahasia123'),
            'role' => 'buyer',
        ]);
    }

    private function createCreator(string $email = 'creator@comicraft.test'): User
    {
        return User::create([
            'name' => 'ComiCraft Creator',
            'email' => $email,
            'phone_number' => '081234567891',
            'password' => Hash::make('rahasia123'),
            'role' => 'creator',
        ]);
    }

    private function createMerchandise(
        int $price = 45000,
        int $stockQuota = 10,
        ?User $creator = null,
    ): Merchandise {
        $creator ??= $this->createCreator();
        $category = FandomCategory::create([
            'name' => 'Honkai Star Rail',
            'slug' => 'honkai-star-rail',
        ]);

        return $creator->merchandises()->create([
            'fandom_category_id' => $category->id,
            'name' => 'Shiroko Shanhaijing Art Print A4 Hologram',
            'item_type' => 'art_print',
            'price' => $price,
            'stock_quota' => $stockQuota,
        ]);
    }

    private function createPreorder(User $buyer, Merchandise $merchandise, string $code): Preorder
    {
        return $merchandise->preorders()->create([
            'user_id' => $buyer->id,
            'preorder_code' => $code,
            'quantity' => 1,
            'total_price' => $merchandise->price,
            'pickup_method' => 'booth_pickup',
            'status' => 'pending',
        ]);
    }
}
