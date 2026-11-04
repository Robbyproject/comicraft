<?php

namespace Tests\Feature;

use App\Models\BoothRental;
use App\Models\ConventionEvent;
use App\Models\FandomCategory;
use App\Models\Merchandise;
use App\Models\Preorder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_creator_sees_operational_overview_dashboard(): void
    {
        $this->actingAs($this->createCreator())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Operational Overview')
            ->assertSee('Slots Left');
    }

    public function test_creator_sees_merch_and_quota_page(): void
    {
        $this->actingAs($this->createCreator())
            ->get(route('admin.merchandises'))
            ->assertOk()
            ->assertSee('Merchandise &amp; Production Quota', false);
    }

    public function test_creator_can_upload_merchandise_image(): void
    {
        Storage::fake('public');
        $creator = $this->createCreator();
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);

        $this->actingAs($creator)
            ->post(route('admin.merchandises.store'), [
                'fandom_category_id' => $category->id,
                'name' => 'Shiroko Acrylic Stand',
                'item_type' => 'acrylic_stand',
                'price' => 45000,
                'stock_quota' => 10,
                'image' => UploadedFile::fake()->image('shiroko.png')->size(12 * 1024),
            ])
            ->assertRedirect();

        $merchandise = Merchandise::where('name', 'Shiroko Acrylic Stand')->firstOrFail();

        Storage::disk('public')->assertExists($merchandise->image);
        $this->assertDatabaseHas('merchandises', [
            'id' => $merchandise->id,
            'creator_id' => $creator->id,
            'image' => $merchandise->image,
        ]);
    }

    public function test_creator_can_upload_merchandise_image_to_configured_disk(): void
    {
        Storage::fake('supabase');
        config(['filesystems.merchandise_disk' => 'supabase']);
        $creator = $this->createCreator();
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);

        $this->actingAs($creator)
            ->post(route('admin.merchandises.store'), [
                'fandom_category_id' => $category->id,
                'name' => 'Shiroko Acrylic Stand',
                'item_type' => 'acrylic_stand',
                'price' => 45000,
                'stock_quota' => 10,
                'image' => UploadedFile::fake()->image('shiroko.png')->size(12 * 1024),
            ])
            ->assertRedirect();

        $merchandise = Merchandise::where('name', 'Shiroko Acrylic Stand')->firstOrFail();

        Storage::disk('supabase')->assertExists($merchandise->image);
        $this->assertStringContainsString('/merchandise-images/', $merchandise->imageUrl());
        $this->assertDatabaseHas('merchandises', [
            'id' => $merchandise->id,
            'creator_id' => $creator->id,
            'image' => $merchandise->image,
        ]);
    }

    public function test_creator_can_replace_merchandise_image(): void
    {
        Storage::fake('supabase');
        config(['filesystems.merchandise_disk' => 'supabase']);
        $creator = $this->createCreator();
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $oldImage = 'merchandise-images/old-image.png';
        Storage::disk('supabase')->put($oldImage, 'old image');
        $merchandise = Merchandise::create([
            'creator_id' => $creator->id,
            'fandom_category_id' => $category->id,
            'name' => 'Shiroko Acrylic Stand',
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 10,
            'image' => $oldImage,
        ]);

        $this->actingAs($creator)
            ->put(route('admin.merchandises.update', $merchandise->id), [
                'fandom_category_id' => $category->id,
                'name' => 'Shiroko Acrylic Stand',
                'item_type' => 'acrylic_stand',
                'price' => 45000,
                'stock_quota' => 10,
                'image' => UploadedFile::fake()->image('shiroko-updated.png'),
            ])
            ->assertRedirect(route('admin.merchandises'));

        $merchandise->refresh();

        $this->assertNotSame($oldImage, $merchandise->image);
        Storage::disk('supabase')->assertExists($merchandise->image);
        Storage::disk('supabase')->assertMissing($oldImage);
    }

    public function test_creator_can_delete_merchandise_image_from_configured_disk(): void
    {
        Storage::fake('supabase');
        config(['filesystems.merchandise_disk' => 'supabase']);
        $creator = $this->createCreator();
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $image = 'merchandise-images/shiroko.png';
        Storage::disk('supabase')->put($image, 'image contents');
        $merchandise = Merchandise::create([
            'creator_id' => $creator->id,
            'fandom_category_id' => $category->id,
            'name' => 'Shiroko Acrylic Stand',
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 10,
            'image' => $image,
        ]);

        $this->actingAs($creator)
            ->delete(route('admin.merchandises.destroy', $merchandise->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('merchandises', ['id' => $merchandise->id]);
        Storage::disk('supabase')->assertMissing($image);
    }

    public function test_creator_upload_failure_is_logged_with_php_error_code(): void
    {
        Log::spy();
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $failedUpload = new UploadedFile(
            'missing-upload.tmp',
            'shiroko.jpg',
            'image/jpeg',
            UPLOAD_ERR_CANT_WRITE,
            true,
        );

        $this->actingAs($this->createCreator())
            ->post(route('admin.merchandises.store'), [
                'fandom_category_id' => $category->id,
                'name' => 'Shiroko Acrylic Stand',
                'item_type' => 'acrylic_stand',
                'price' => 45000,
                'stock_quota' => 10,
                'image' => $failedUpload,
            ])
            ->assertSessionHasErrors('image');

        Log::shouldHaveReceived('warning')
            ->once()
            ->withArgs(fn (string $message, array $context): bool => $message === 'Merchandise image upload failed before validation.'
                && $context['upload_error_code'] === UPLOAD_ERR_CANT_WRITE);
    }

    public function test_creator_sees_preorder_queue_page(): void
    {
        $this->actingAs($this->createCreator())
            ->get(route('admin.preorders'))
            ->assertOk()
            ->assertSee('Semua Order');
    }

    public function test_creator_sees_support_inbox_page(): void
    {
        $this->actingAs($this->createCreator())
            ->get(route('admin.support'))
            ->assertOk()
            ->assertSee('Semua Tiket');
    }

    public function test_creator_booth_page_hides_other_creators_booking_details(): void
    {
        $creator = $this->createCreator();
        $otherCreator = $this->createCreator('other-creator@comicraft.test');
        $event = ConventionEvent::create([
            'name' => 'ComiCraft Convention',
            'venue' => 'Jakarta',
            'event_date' => '2026-12-01',
            'status' => 'open_registration',
        ]);
        BoothRental::create([
            'convention_event_id' => $event->id,
            'creator_id' => $creator->id,
            'booth_number' => 'A-01',
            'booth_type' => 'standard_2x2',
            'base_price' => 100000,
            'platform_fee' => 5000,
            'total_rental_cost' => 105000,
            'rental_status' => 'paid_confirmed',
            'circle_name' => 'My Private Circle',
        ]);
        BoothRental::create([
            'convention_event_id' => $event->id,
            'creator_id' => $otherCreator->id,
            'booth_number' => 'A-02',
            'booth_type' => 'standard_2x2',
            'base_price' => 100000,
            'platform_fee' => 5000,
            'total_rental_cost' => 105000,
            'rental_status' => 'paid_confirmed',
            'circle_name' => 'Other Private Circle',
        ]);

        $this->actingAs($creator)
            ->get(route('admin.booths'))
            ->assertOk()
            ->assertSee('My Private Circle')
            ->assertDontSee('Other Private Circle');
    }

    public function test_creator_only_sees_and_manages_its_own_products_orders_and_support_tickets(): void
    {
        $creator = $this->createCreator();
        $otherCreator = $this->createCreator('other-creator@comicraft.test');
        $buyer = $this->createBuyer();
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $merchandise = $this->createMerchandise($creator, $category, 'Creator One Item');
        $otherMerchandise = $this->createMerchandise($otherCreator, $category, 'Creator Two Item');
        $order = $this->createPreorder($buyer, $merchandise, 'PO-CREATOR1');
        $otherOrder = $this->createPreorder($buyer, $otherMerchandise, 'PO-CREATOR2');
        $ticket = $buyer->supportMessages()->create([
            'creator_id' => $creator->id,
            'preorder_id' => $order->id,
            'subject' => 'Creator One Ticket',
            'message' => 'My order question',
            'status' => 'waiting_reply',
        ]);
        $otherTicket = $buyer->supportMessages()->create([
            'creator_id' => $otherCreator->id,
            'preorder_id' => $otherOrder->id,
            'subject' => 'Creator Two Ticket',
            'message' => 'Another creator question',
            'status' => 'waiting_reply',
        ]);
        $order->update([
            'payment_status' => 'released_to_creator',
            'net_creator_income' => 42000,
        ]);
        $otherOrder->update([
            'payment_status' => 'released_to_creator',
            'net_creator_income' => 41000,
        ]);

        $this->actingAs($creator)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee($merchandise->name)
            ->assertSee($order->preorder_code)
            ->assertSee($ticket->subject)
            ->assertDontSee($otherMerchandise->name)
            ->assertDontSee($otherOrder->preorder_code)
            ->assertDontSee($otherTicket->subject);

        $this->get(route('admin.preorders'))
            ->assertOk()
            ->assertSee($order->preorder_code)
            ->assertDontSee($otherOrder->preorder_code);

        $this->get(route('admin.support'))
            ->assertOk()
            ->assertSee($ticket->subject)
            ->assertDontSee($otherTicket->subject);

        $this->get(route('admin.transactions'))
            ->assertOk()
            ->assertSee($order->preorder_code)
            ->assertDontSee($otherOrder->preorder_code);

        $this->get(route('admin.merchandises', ['edit' => $otherMerchandise->id]))
            ->assertNotFound();

        $this->put(route('admin.merchandises.update', $otherMerchandise), [
            'fandom_category_id' => $category->id,
            'name' => 'Updated by wrong creator',
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 10,
        ])->assertNotFound();

        $this->delete(route('admin.merchandises.destroy', $otherMerchandise))
            ->assertNotFound();

        $this->patch(route('admin.orders.updateStatus', $otherOrder), ['status' => 'producing'])
            ->assertNotFound();
        $this->delete(route('admin.orders.destroy', $otherOrder))
            ->assertNotFound();

        $this->patch(route('admin.support.reply', $otherTicket), ['admin_reply' => 'Unauthorized reply'])
            ->assertNotFound();

        $this->assertDatabaseHas('merchandises', [
            'id' => $otherMerchandise->id,
            'name' => $otherMerchandise->name,
        ]);
        $this->assertDatabaseHas('preorders', [
            'id' => $otherOrder->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('support_messages', [
            'id' => $otherTicket->id,
            'admin_reply' => null,
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

    private function createBuyer(): User
    {
        return User::create([
            'name' => 'Buyer',
            'email' => 'buyer@comicraft.test',
            'phone_number' => '081234567890',
            'password' => Hash::make('rahasia123'),
            'role' => 'buyer',
        ]);
    }

    private function createMerchandise(User $creator, FandomCategory $category, string $name): Merchandise
    {
        return $creator->merchandises()->create([
            'fandom_category_id' => $category->id,
            'name' => $name,
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 10,
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
