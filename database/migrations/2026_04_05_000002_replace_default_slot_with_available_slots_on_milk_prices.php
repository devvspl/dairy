<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milk_prices', function (Blueprint $table) {
            $table->dropColumn('default_slot');
            $table->json('available_slots')->nullable()->after('cutoff_time');
        });
    }

    public function down(): void
    {
        Schema::table('milk_prices', function (Blueprint $table) {
            $table->dropColumn('available_slots');
            $table->enum('default_slot', ['morning', 'evening'])->default('evening')->after('cutoff_time');
        });
    }
};
