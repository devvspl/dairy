<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted'])->default('earned');
            $table->string('reason');
            $table->text('description')->nullable();
            $table->foreignId('related_order_id')->nullable()->constrained('user_subscriptions')->onDelete('set null');
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });

        // Add loyalty points balance to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('loyalty_points_balance')->default(0)->after('profile_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('loyalty_points_balance');
        });
        
        Schema::dropIfExists('loyalty_points');
    }
};
