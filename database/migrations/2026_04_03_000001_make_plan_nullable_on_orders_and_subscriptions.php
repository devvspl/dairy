<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allow wallet-only orders/subscriptions with no plan
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('membership_plan_id')->nullable()->change();
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->foreignId('membership_plan_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('membership_plan_id')->nullable(false)->change();
        });
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->foreignId('membership_plan_id')->nullable(false)->change();
        });
    }
};
