<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shiprocket_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('pickup_location')->default('Primary');
            $table->string('default_city')->nullable();
            $table->string('default_state')->nullable();
            $table->string('default_pincode')->nullable();
            $table->decimal('pkg_length', 8, 2)->default(10);
            $table->decimal('pkg_breadth', 8, 2)->default(10);
            $table->decimal('pkg_height', 8, 2)->default(10);
            $table->decimal('pkg_weight', 8, 2)->default(0.5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shiprocket_settings');
    }
};
