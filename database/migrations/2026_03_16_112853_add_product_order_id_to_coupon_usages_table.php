<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupon_usages', function (Blueprint $table) {
            $table->unsignedBigInteger('product_order_id')->nullable()->after('order_id');
            $table->foreign('product_order_id')->references('id')->on('product_orders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('coupon_usages', function (Blueprint $table) {
            $table->dropForeign(['product_order_id']);
            $table->dropColumn('product_order_id');
        });
    }
};
