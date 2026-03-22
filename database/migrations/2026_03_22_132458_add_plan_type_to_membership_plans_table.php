<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->string('plan_type')->default('scheduled')->after('slug');
            $table->integer('max_orders_per_month')->nullable()->after('plan_type');
            $table->decimal('product_discount_percent', 5, 2)->nullable()->after('max_orders_per_month');
        });
    }

    public function down(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropColumn(['plan_type', 'max_orders_per_month', 'product_discount_percent']);
        });
    }
};
