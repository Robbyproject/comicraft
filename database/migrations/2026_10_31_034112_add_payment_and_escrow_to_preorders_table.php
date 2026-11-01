<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->enum('payment_scheme', ['full_payment', 'down_payment'])->default('full_payment')->after('pickup_method');
            $table->unsignedInteger('amount_paid')->default(0)->after('payment_scheme');
            $table->unsignedInteger('remaining_balance')->default(0)->after('amount_paid');
            $table->unsignedInteger('platform_fee')->default(0)->after('remaining_balance');
            $table->unsignedInteger('net_creator_income')->default(0)->after('platform_fee');
            $table->enum('payment_status', ['unpaid', 'paid_in_escrow', 'released_to_creator'])->default('paid_in_escrow')->after('net_creator_income');
        });
    }

    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_scheme',
                'amount_paid',
                'remaining_balance',
                'platform_fee',
                'net_creator_income',
                'payment_status',
            ]);
        });
    }
};
