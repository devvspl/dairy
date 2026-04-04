<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milk_wallet_transactions', function (Blueprint $table) {
            // Link transaction to the specific delivery log that caused it
            $table->foreignId('delivery_log_id')
                  ->nullable()
                  ->after('user_subscription_id')
                  ->constrained('delivery_logs')
                  ->onDelete('set null');
            // Track if this is a reversal credit (should not inflate wallet_total)
            $table->boolean('is_reversal')->default(false)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('milk_wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['delivery_log_id']);
            $table->dropColumn(['delivery_log_id', 'is_reversal']);
        });
    }
};
