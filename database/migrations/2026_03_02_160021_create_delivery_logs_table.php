<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_subscription_id')->constrained()->onDelete('cascade');
            $table->date('delivery_date');
            $table->decimal('quantity_delivered', 8, 2); // Quantity in liters
            $table->enum('status', ['pending', 'delivered', 'skipped', 'failed'])->default('pending');
            $table->time('delivery_time')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('marked_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who marked it
            $table->timestamp('marked_at')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['user_subscription_id', 'delivery_date']);
            $table->index('delivery_date');
            $table->index('status');
            
            // Unique constraint to prevent duplicate entries for same subscription and date
            $table->unique(['user_subscription_id', 'delivery_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_logs');
    }
};
