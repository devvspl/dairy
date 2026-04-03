<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milk_prices', function (Blueprint $table) {
            $table->id();
            $table->string('milk_type', 50)->unique(); // cow, buffalo, toned, full_fat
            $table->string('label', 100);              // display name
            $table->decimal('price_per_litre', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milk_prices');
    }
};
