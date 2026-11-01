<?php

namespace Tests\Feature;

use App\Models\FandomCategory;
use App\Models\Merchandise;
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
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);

        $this->actingAs($this->createCreator())
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
            'image' => $merchandise->image,
        ]);
    }

    public function test_creator_can_upload_merchandise_image_to_configured_disk(): void
    {
        Storage::fake('supabase');
        config(['filesystems.merchandise_disk' => 'supabase']);
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);

        $this->actingAs($this->createCreator())
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
            'image' => $merchandise->image,
        ]);
    }

    public function test_creator_can_replace_merchandise_image(): void
    {
        Storage::fake('supabase');
        config(['filesystems.merchandise_disk' => 'supabase']);
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $oldImage = 'merchandise-images/old-image.png';
        Storage::disk('supabase')->put($oldImage, 'old image');
        $merchandise = Merchandise::create([
            'fandom_category_id' => $category->id,
            'name' => 'Shiroko Acrylic Stand',
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 10,
            'image' => $oldImage,
        ]);

        $this->actingAs($this->createCreator())
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
        $category = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $image = 'merchandise-images/shiroko.png';
        Storage::disk('supabase')->put($image, 'image contents');
        $merchandise = Merchandise::create([
            'fandom_category_id' => $category->id,
            'name' => 'Shiroko Acrylic Stand',
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 10,
            'image' => $image,
        ]);

        $this->actingAs($this->createCreator())
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

    private function createCreator(): User
    {
        return User::create([
            'name' => 'ComiCraft Creator',
            'email' => 'creator@comicraft.test',
            'phone_number' => '081234567891',
            'password' => Hash::make('rahasia123'),
            'role' => 'creator',
        ]);
    }
}
