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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('area')->nullable();
            $table->string('sector')->nullable();
            $table->string('city')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            
            // Building Information
            $table->string('building_name')->nullable();
            $table->string('building_type')->nullable();
            $table->string('delivery_timing')->nullable();
            $table->string('delivery_point')->nullable();
            $table->text('handling_info')->nullable();
            
            // Address & Map
            $table->text('address')->nullable();
            $table->text('map_embed_url')->nullable();
            
            // JSON Fields
            $table->json('hero_badges')->nullable();
            $table->json('route_steps')->nullable();
            $table->json('highlights')->nullable();
            $table->json('mini_items')->nullable();
            $table->json('guidelines')->nullable();
            $table->json('coverage_areas')->nullable();
            $table->json('faqs')->nullable();
            
            // Contact
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Status & Order
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
