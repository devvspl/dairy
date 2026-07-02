<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->string('delivery_status')->nullable()->after('skip_shiprocket'); // pending, delivered
            $table->timestamp('delivered_at')->nullable()->after('delivery_status');
            $table->string('delivery_notes')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_status', 'delivered_at', 'delivery_notes']);
        });
    }
};
