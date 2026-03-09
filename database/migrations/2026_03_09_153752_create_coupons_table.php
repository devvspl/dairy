<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('min_purchase_amount', 10, 2)->default(0);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // null = unlimited
            $table->integer('usage_per_user')->default(1);
            $table->integer('times_used')->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->boolean('is_active')->default(true);
            $table->enum('applicable_to', ['all', 'membership', 'products'])->default('all');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
