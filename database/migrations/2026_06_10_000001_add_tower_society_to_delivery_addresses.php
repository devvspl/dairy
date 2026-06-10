<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->string('tower')->nullable()->after('flat_no');    // Tower / Block name
            $table->string('society')->nullable()->after('tower');    // Society / Building name
        });
    }

    public function down(): void
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->dropColumn(['tower', 'society']);
        });
    }
};
