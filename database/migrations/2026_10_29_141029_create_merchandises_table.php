<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fandom_category_id')->constrained('fandom_categories')->cascadeOnDelete();
            $table->string('name');
            $table->enum('item_type', ['acrylic_stand', 'keychain', 'photocard', 'art_print']);
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock_quota');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandises');
    }
};
