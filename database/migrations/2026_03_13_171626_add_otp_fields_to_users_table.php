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
        Schema::table('users', function (Blueprint $table) {
            // Make email nullable for OTP-based registration
            $table->string('email')->nullable()->change();
            
            // Make password nullable for OTP-based authentication
            $table->string('password')->nullable()->change();
            
            // Add OTP fields
            $table->string('otp', 6)->nullable()->after('password');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->timestamp('otp_verified_at')->nullable()->after('otp_expires_at');
            
            // Add mobile verification
            $table->timestamp('mobile_verified_at')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp', 'otp_expires_at', 'otp_verified_at', 'mobile_verified_at']);
            
            // Revert email and password to not nullable
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });
    }
};
