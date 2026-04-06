<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add milk_items JSON to delivery settings
        // milk_items: [{"milk_type":"cow","qty":1,"slot":"morning","ppl":70.00}, ...]
        Schema::table('subscription_delivery_settings', function (Blueprint $table) {
            $table->json('milk_items')->nullable()->after('delivery_slot');
        });

        // Add milk_items JSON to delivery logs so each log records what was delivered
        Schema::table('delivery_logs', function (Blueprint $table) {
            $table->json('milk_items')->nullable()->after('quantity_delivered');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_delivery_settings', function (Blueprint $table) {
            $table->dropColumn('milk_items');
        });
        Schema::table('delivery_logs', function (Blueprint $table) {
            $table->dropColumn('milk_items');
        });
    }
};
