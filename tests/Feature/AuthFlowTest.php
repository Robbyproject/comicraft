<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_only_see_the_welcome_page(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Sign In')
            ->assertSee('Sign Up');

        // Semua halaman buyer diarahkan ke form login saat belum login.
        $this->get(route('katalog'))->assertRedirect(route('login'));
        $this->get(route('support.create'))->assertRedirect(route('login'));
        $this->get(route('buyer.orders'))->assertRedirect(route('login'));
    }

    public function test_register_creates_buyer_and_redirects_to_login_without_auto_login(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Robby Buyer',
            'email' => 'buyer@comicraft.test',
            'phone_number' => '081234567890',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'buyer',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();

        $this->assertDatabaseHas('users', [
            'email' => 'buyer@comicraft.test',
            'role' => 'buyer',
        ]);
    }

    public function test_registered_creator_receives_creator_role(): void
    {
        $this->post(route('register.submit'), [
            'name' => 'Robby Creator',
            'email' => 'creator@comicraft.test',
            'phone_number' => '081234567891',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'creator',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'creator@comicraft.test',
            'role' => 'creator',
        ]);
    }

    public function test_buyer_login_redirects_to_katalog_dashboard(): void
    {
        $buyer = User::create([
            'name' => 'Robby Buyer',
            'email' => 'buyer@comicraft.test',
            'phone_number' => '081234567890',
            'password' => Hash::make('rahasia123'),
            'role' => 'buyer',
        ]);

        $this->post(route('login.submit'), [
            'email' => $buyer->email,
            'password' => 'rahasia123',
        ])->assertRedirect(route('katalog'));
    }

    public function test_creator_login_redirects_to_creator_dashboard(): void
    {
        $creator = User::create([
            'name' => 'Robby Creator',
            'email' => 'creator@comicraft.test',
            'phone_number' => '081234567891',
            'password' => Hash::make('rahasia123'),
            'role' => 'creator',
        ]);

        // Bahkan jika ada URL intended (misal sebelumnya guest klik katalog), kreator tetap ke dashboard kreator
        $this->withSession(['url.intended' => route('katalog')])
            ->post(route('login.submit'), [
                'email' => $creator->email,
                'password' => 'rahasia123',
            ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_logged_in_user_can_logout(): void
    {
        $buyer = User::create([
            'name' => 'Robby Buyer',
            'email' => 'buyer@comicraft.test',
            'phone_number' => '081234567890',
            'password' => Hash::make('rahasia123'),
            'role' => 'buyer',
        ]);

        $this->actingAs($buyer)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
