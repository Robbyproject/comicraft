<?php

namespace Database\Seeders;

use App\Models\BoothRental;
use App\Models\ConventionEvent;
use App\Models\FandomCategory;
use App\Models\Merchandise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Creator Booth
        $creator = User::create([
            'name' => 'ComiCraft Creator',
            'email' => 'creator@comicraft.test',
            'password' => Hash::make('password123'),
            'role' => 'creator',
            'phone_number' => '081234567890',
        ]);

        // 2. Akun Buyer (Pengunjung / Wibu)
        $buyer = User::create([
            'name' => 'Robby Fabian (Buyer)',
            'email' => 'buyer@comicraft.test',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'phone_number' => '089876543210',
        ]);

        // 3. Kategori Fandom Anime
        $blueArchive = FandomCategory::create(['name' => 'Blue Archive', 'slug' => 'blue-archive']);
        $genshin = FandomCategory::create(['name' => 'Genshin Impact', 'slug' => 'genshin-impact']);
        $frieren = FandomCategory::create(['name' => 'Sousou no Frieren', 'slug' => 'sousou-no-frieren']);

        // ==========================================
        // 4. MERCHANDISE SESUAI KARAKTER BLUE ARCHIVE
        // ==========================================

        // --- SHIROKO SUNAOOKAMI ---
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Shiroko Riding Suit Chibi Acrylic Keychain 6cm',
            'item_type' => 'keychain',
            'price' => 25000,
            'stock_quota' => 60,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Shiroko Sunaookami Cycling Art Print A4 Hologram',
            'item_type' => 'art_print',
            'price' => 20000,
            'stock_quota' => 50,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Shiroko Abydos Sandstorm Acrylic Standee 15cm',
            'item_type' => 'acrylic_stand',
            'price' => 50000,
            'stock_quota' => 35,
        ]);

        // --- YUUKA HAYASE ---
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Yuuka Pajama Angel Chicken Acrylic Keychain 6cm',
            'item_type' => 'keychain',
            'price' => 25000,
            'stock_quota' => 75,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Yuuka Seminar Calculator Hologram Art Print A4',
            'item_type' => 'art_print',
            'price' => 20000,
            'stock_quota' => 45,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Yuuka Hayase Seminar Acrylic Standee 14cm',
            'item_type' => 'acrylic_stand',
            'price' => 48000,
            'stock_quota' => 40,
        ]);

        // --- SAORI JOUMAE ---
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Saori Swimsuit Summer Night Chibi Keychain 6cm',
            'item_type' => 'keychain',
            'price' => 25000,
            'stock_quota' => 50,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Saori Arius Squad Beach Holiday Art Print A4',
            'item_type' => 'art_print',
            'price' => 22000,
            'stock_quota' => 40,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Saori Joumae Memorial Lobby Acrylic Standee 16cm',
            'item_type' => 'acrylic_stand',
            'price' => 55000,
            'stock_quota' => 30,
        ]);

        // --- MIDORI SAIBA ---
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Midori Game Dev Chibi Crayon Acrylic Keychain 6cm',
            'item_type' => 'keychain',
            'price' => 25000,
            'stock_quota' => 60,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Midori & Momoi Twin Gamers Art Print A4 Hologram',
            'item_type' => 'art_print',
            'price' => 20000,
            'stock_quota' => 50,
        ]);
        Merchandise::create([
            'fandom_category_id' => $blueArchive->id,
            'name' => 'Midori Saiba Game Development Club Standee 15cm',
            'item_type' => 'acrylic_stand',
            'price' => 50000,
            'stock_quota' => 35,
        ]);

        // --- MERCHANDISE FANDOM LAINNYA ---
        Merchandise::create([
            'fandom_category_id' => $genshin->id,
            'name' => 'Furina Hydro Archon Acrylic Standee 15cm',
            'item_type' => 'acrylic_stand',
            'price' => 45000,
            'stock_quota' => 50,
        ]);
        Merchandise::create([
            'fandom_category_id' => $frieren->id,
            'name' => 'Chibi Frieren Mimic Keychain Glitter',
            'item_type' => 'keychain',
            'price' => 25000,
            'stock_quota' => 100,
        ]);

        // ==========================================
        // 5. DATA EVENT & SEWA BOOTH
        // ==========================================
        $comifuro = ConventionEvent::create([
            'name' => 'Comifuro 19',
            'venue' => 'Hall 8-10 ICE BSD City, Tangerang',
            'event_date' => '2026-11-15',
            'status' => 'open_registration',
        ]);

        $afa = ConventionEvent::create([
            'name' => 'Anime Festival Asia (AFAID 2026)',
            'venue' => 'Jakarta Convention Center (JCC) Senayan',
            'event_date' => '2026-12-05',
            'status' => 'open_registration',
        ]);

        // Booth B-12 yang sudah disewa creator
        BoothRental::create([
            'convention_event_id' => $comifuro->id,
            'creator_id' => $creator->id,
            'booth_number' => 'B-12',
            'booth_type' => 'corner_2x2',
            'base_price' => 1500000,
            'platform_fee' => 75000,
            'total_rental_cost' => 1575000,
            'rental_status' => 'paid_confirmed',
            'notes' => 'Booth Kreator Utama ComiCraft',
        ]);

        // Slot booth available
        BoothRental::create([
            'convention_event_id' => $comifuro->id,
            'creator_id' => null,
            'booth_number' => 'B-13',
            'booth_type' => 'standard_2x2',
            'base_price' => 1200000,
            'platform_fee' => 60000,
            'total_rental_cost' => 1260000,
            'rental_status' => 'available',
        ]);

        BoothRental::create([
            'convention_event_id' => $comifuro->id,
            'creator_id' => null,
            'booth_number' => 'VIP-01',
            'booth_type' => 'premium_island',
            'base_price' => 3000000,
            'platform_fee' => 150000,
            'total_rental_cost' => 3150000,
            'rental_status' => 'available',
        ]);

        BoothRental::create([
            'convention_event_id' => $afa->id,
            'creator_id' => null,
            'booth_number' => 'AFA-08',
            'booth_type' => 'corner_2x2',
            'base_price' => 2000000,
            'platform_fee' => 100000,
            'total_rental_cost' => 2100000,
            'rental_status' => 'available',
        ]);
    }
}
