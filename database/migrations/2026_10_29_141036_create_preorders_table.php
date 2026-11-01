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
        Schema::create('preorders', function (Blueprint $table) {
            $table->id();
            $table->string('preorder_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('merchandise_id')->constrained('merchandises')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('total_price');
            $table->enum('pickup_method', ['booth_pickup', 'shipping'])->default('booth_pickup');
            $table->enum('status', ['pending', 'producing', 'ready_at_booth', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorders');
    }
};
