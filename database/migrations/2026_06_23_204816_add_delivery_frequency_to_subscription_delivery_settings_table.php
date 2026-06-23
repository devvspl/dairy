<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_delivery_settings', function (Blueprint $table) {
            // delivery_frequency: daily (default), alternate, weekly
            $table->string('delivery_frequency', 20)->default('daily')->after('delivery_instructions');
            // preferred_day: used when frequency is 'weekly' (0=Sunday, 1=Monday, ... 6=Saturday)
            $table->unsignedTinyInteger('preferred_day')->nullable()->after('delivery_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_delivery_settings', function (Blueprint $table) {
            $table->dropColumn(['delivery_frequency', 'preferred_day']);
        });
    }
};
