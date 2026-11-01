<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            // PIN rahasia serah-terima di booth (hanya dipegang pembeli)
            $table->string('pickup_secret_pin', 6)->nullable()->after('preorder_code');
            // Nomor resi ekspedisi jika dikirim kurir
            $table->string('tracking_number')->nullable()->after('pickup_method');
        });
    }

    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn(['pickup_secret_pin', 'tracking_number']);
        });
    }
};
