<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new category_id column
            $table->foreignId('category_id')->nullable()->after('description')->constrained('categories')->onDelete('set null');
            // Add new type_id column
            $table->foreignId('type_id')->nullable()->after('category_id')->constrained('types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }
};
