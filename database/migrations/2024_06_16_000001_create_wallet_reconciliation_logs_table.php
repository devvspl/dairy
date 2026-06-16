<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_reconciliation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('fix_type');                     // e.g. rebuild_from_ledger, fix_from_deliveries, mark_reconciled
            $table->decimal('before_balance', 12, 2);
            $table->decimal('after_balance', 12, 2);
            $table->decimal('difference', 12, 2);           // after - before
            $table->decimal('expected_balance', 12, 2);     // what ledger says it should be
            $table->decimal('actual_balance', 12, 2);       // wallet_balance at time of action
            $table->json('meta')->nullable();               // any extra context
            $table->string('status')->default('success');   // success | failed | skipped
            $table->text('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['user_subscription_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_reconciliation_logs');
    }
};
