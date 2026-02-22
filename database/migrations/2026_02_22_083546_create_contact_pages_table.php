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
        Schema::create('contact_pages', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            
            // Hero Section
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_phone')->nullable();
            $table->string('hero_email')->nullable();
            
            // Contact Cards
            $table->string('phone_title')->nullable();
            $table->text('phone_description')->nullable();
            $table->string('phone_number')->nullable();
            
            $table->string('email_title')->nullable();
            $table->text('email_description')->nullable();
            $table->string('email_address')->nullable();
            
            $table->string('address_title')->nullable();
            $table->text('address_description')->nullable();
            $table->text('address_full')->nullable();
            
            // Map Section
            $table->string('map_title')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->string('map_link')->nullable();
            
            // FAQ
            $table->json('faqs')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_pages');
    }
};
