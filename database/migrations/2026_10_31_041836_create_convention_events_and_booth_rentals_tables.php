<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Event Pameran Anime (Comifuro, AFA, dll)
        Schema::create('convention_events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Comifuro 19
            $table->string('venue'); // Contoh: Hall 9-10 ICE BSD
            $table->date('event_date');
            $table->enum('status', ['open_registration', 'ongoing', 'closed'])->default('open_registration');
            $table->timestamps();
        });

        // 2. Tabel Sewa Booth Kreator
        Schema::create('booth_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convention_event_id')->constrained('convention_events')->cascadeOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('booth_number'); // Contoh: B-12, A-05, VIP-01
            $table->enum('booth_type', ['standard_2x2', 'corner_2x2', 'premium_island'])->default('standard_2x2');
            $table->unsignedInteger('base_price'); // Harga sewa asli dari event
            $table->unsignedInteger('platform_fee'); // Pajak platform 5%
            $table->unsignedInteger('total_rental_cost'); // base_price + platform_fee
            $table->enum('rental_status', ['available', 'reserved', 'paid_confirmed'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booth_rentals');
        Schema::dropIfExists('convention_events');
    }
};
