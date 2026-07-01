<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_subscription_redirect')->default(false)->after('is_featured')
                ->comment('If true, clicking this product redirects to member dashboard (subscription) instead of product detail');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_subscription_redirect');
        });
    }
};
