<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->foreignId('creator_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        Schema::table('support_messages', function (Blueprint $table) {
            $table->foreignId('creator_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $creatorId = DB::table('users')
            ->where('role', 'creator')
            ->orderBy('id')
            ->value('id');

        if ($creatorId !== null) {
            DB::table('merchandises')
                ->whereNull('creator_id')
                ->update(['creator_id' => $creatorId]);

            DB::table('support_messages')
                ->whereNull('preorder_id')
                ->whereNull('creator_id')
                ->update(['creator_id' => $creatorId]);
        }

        DB::table('support_messages')
            ->whereNotNull('preorder_id')
            ->update([
                'creator_id' => DB::raw('(SELECT merchandises.creator_id FROM preorders INNER JOIN merchandises ON merchandises.id = preorders.merchandise_id WHERE preorders.id = support_messages.preorder_id)'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('creator_id');
        });

        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropConstrainedForeignId('creator_id');
        });
    }
};
