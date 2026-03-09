<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add pivot tables for specific membership plans and products
        Schema::create('coupon_membership_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_plan_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('coupon_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Add column to track if coupon applies to specific items
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('apply_to_specific_items')->default(false)->after('applicable_to');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('apply_to_specific_items');
        });
        
        Schema::dropIfExists('coupon_product');
        Schema::dropIfExists('coupon_membership_plan');
    }
};
