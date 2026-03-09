<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->integer('total_referrals')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('referral_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_code_id')->constrained()->onDelete('cascade');
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('referrer_reward', 10, 2)->default(0);
            $table->decimal('referee_reward', 10, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_usages');
        Schema::dropIfExists('referral_codes');
    }
};
