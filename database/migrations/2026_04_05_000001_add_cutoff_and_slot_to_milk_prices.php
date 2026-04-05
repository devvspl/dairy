<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milk_prices', function (Blueprint $table) {
            // Order cutoff time — orders placed after this time apply from next day
            $table->time('cutoff_time')->default('20:00:00')->after('price_per_litre');
            // Default delivery slot for this milk type
            $table->enum('default_slot', ['morning', 'evening'])->default('evening')->after('cutoff_time');
        });
    }

    public function down(): void
    {
        Schema::table('milk_prices', function (Blueprint $table) {
            $table->dropColumn(['cutoff_time', 'default_slot']);
        });
    }
};
