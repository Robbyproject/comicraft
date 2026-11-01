<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booth_rentals', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->nullable()->after('id');
            $table->string('circle_name')->nullable()->after('creator_id');
            $table->string('pic_name')->nullable()->after('circle_name'); // Nama Penanggung Jawab
            $table->string('whatsapp_number')->nullable()->after('pic_name');
            $table->string('merchandise_plan')->nullable()->after('whatsapp_number');
            $table->string('payment_method')->default('qris_virtual')->after('total_rental_cost');
            $table->timestamp('paid_at')->nullable()->after('rental_status');
        });
    }

    public function down(): void
    {
        Schema::table('booth_rentals', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number',
                'circle_name',
                'pic_name',
                'whatsapp_number',
                'merchandise_plan',
                'payment_method',
                'paid_at',
            ]);
        });
    }
};
