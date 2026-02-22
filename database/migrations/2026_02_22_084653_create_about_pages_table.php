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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            
            // Hero Section
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('hero_badges')->nullable();
            $table->string('hero_button_1_text')->nullable();
            $table->string('hero_button_1_link')->nullable();
            $table->string('hero_button_2_text')->nullable();
            $table->string('hero_button_2_link')->nullable();
            
            // Overview Section
            $table->string('overview_title')->nullable();
            $table->text('overview_description')->nullable();
            $table->string('overview_image')->nullable();
            $table->string('overview_badge_rating')->nullable();
            $table->string('overview_badge_text')->nullable();
            $table->json('overview_checks')->nullable();
            $table->string('overview_button_text')->nullable();
            $table->string('overview_button_link')->nullable();
            
            // USPs Section
            $table->json('usps')->nullable();
            
            // Counters Section
            $table->json('counters')->nullable();
            
            // Why Choose Us Section
            $table->json('why_items')->nullable();
            $table->string('why_promise_title')->nullable();
            $table->text('why_promise_description')->nullable();
            $table->string('why_promise_button_text')->nullable();
            $table->string('why_promise_button_link')->nullable();
            
            // Team Section
            $table->json('team_members')->nullable();
            
            // FAQ Section
            $table->json('faqs')->nullable();
            
            // Contact Form Section
            $table->string('contact_form_title')->nullable();
            $table->text('contact_form_description')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
