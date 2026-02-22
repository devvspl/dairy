<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Basic info
            $table->string('sku')->nullable()->after('name');
            $table->text('short_description')->nullable()->after('meta');
            $table->longText('description')->nullable()->after('short_description');
            
            // Category
            $table->string('category')->nullable()->after('description');
            
            // Pricing
            $table->decimal('mrp', 10, 2)->nullable()->after('price');
            $table->integer('discount_percent')->default(0)->after('mrp');
            
            // Stock
            $table->string('stock_status')->default('available')->after('discount_percent');
            $table->integer('stock_quantity')->default(0)->after('stock_status');
            
            // Product details
            $table->string('shelf_life')->nullable()->after('stock_quantity');
            $table->string('storage_temp')->nullable()->after('shelf_life');
            $table->string('best_for')->nullable()->after('storage_temp');
            
            // Images (multiple)
            $table->json('images')->nullable()->after('image');
            
            // Pack sizes
            $table->json('pack_sizes')->nullable()->after('images');
            
            // Delivery slots
            $table->json('delivery_slots')->nullable()->after('pack_sizes');
            
            // Specifications
            $table->json('specifications')->nullable()->after('delivery_slots');
            
            // Nutrition info
            $table->json('nutrition_info')->nullable()->after('specifications');
            
            // Storage instructions
            $table->json('storage_instructions')->nullable()->after('nutrition_info');
            
            // Features/Benefits
            $table->json('features')->nullable()->after('storage_instructions');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku',
                'short_description',
                'description',
                'category',
                'mrp',
                'discount_percent',
                'stock_status',
                'stock_quantity',
                'shelf_life',
                'storage_temp',
                'best_for',
                'images',
                'pack_sizes',
                'delivery_slots',
                'specifications',
                'nutrition_info',
                'storage_instructions',
                'features',
            ]);
        });
    }
};
