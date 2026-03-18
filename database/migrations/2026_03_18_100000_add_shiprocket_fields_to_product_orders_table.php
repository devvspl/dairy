<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->string('shiprocket_order_id')->nullable()->after('discount_amount');
            $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_order_id');
            $table->string('shiprocket_awb')->nullable()->after('shiprocket_shipment_id');
            $table->string('shiprocket_courier')->nullable()->after('shiprocket_awb');
            $table->string('shiprocket_status')->nullable()->after('shiprocket_courier');
            $table->timestamp('shiprocket_assigned_at')->nullable()->after('shiprocket_status');
        });
    }

    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropColumn([
                'shiprocket_order_id', 'shiprocket_shipment_id',
                'shiprocket_awb', 'shiprocket_courier',
                'shiprocket_status', 'shiprocket_assigned_at',
            ]);
        });
    }
};
