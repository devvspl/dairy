<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milk_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 10, 2);           // ₹ amount
            $table->decimal('litres', 8, 3)->nullable(); // litres consumed (for debit)
            $table->decimal('balance_after', 10, 2);    // wallet balance after this txn
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();

            $table->index(['user_id', 'transaction_date']);
            $table->index('user_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milk_wallet_transactions');
    }
};
