<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            // Wallet fields for on-demand plans
            $table->decimal('wallet_total', 10, 2)->nullable()->after('amount_paid');   // total credited
            $table->decimal('wallet_balance', 10, 2)->nullable()->after('wallet_total'); // remaining balance
            $table->decimal('price_per_litre', 8, 2)->nullable()->after('wallet_balance'); // ₹ per litre
            $table->string('milk_type', 50)->nullable()->after('price_per_litre');
            $table->decimal('quantity_per_day', 5, 2)->nullable()->after('milk_type');
            $table->string('delivery_slot', 50)->nullable()->after('quantity_per_day');
        });
    }

    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['wallet_total', 'wallet_balance', 'price_per_litre', 'milk_type', 'quantity_per_day', 'delivery_slot']);
        });
    }
};
