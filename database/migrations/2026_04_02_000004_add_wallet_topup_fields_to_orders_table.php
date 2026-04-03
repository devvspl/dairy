<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Distinguish wallet top-up orders from subscription orders
            $table->enum('order_type', ['subscription', 'wallet_topup'])
                  ->default('subscription')
                  ->after('user_id');
            $table->foreignId('user_subscription_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null')
                  ->after('order_type');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_subscription_id']);
            $table->dropColumn(['order_type', 'user_subscription_id']);
        });
    }
};
