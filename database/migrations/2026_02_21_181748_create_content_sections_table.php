<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // why_it_works, video_section, cta_section
            $table->string('kicker')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('points')->nullable(); // For checklist points
            $table->json('buttons')->nullable(); // For CTA buttons
            $table->string('image')->nullable();
            $table->string('video_id')->nullable(); // YouTube video ID
            $table->json('gallery_images')->nullable(); // For video section side images
            $table->json('meta')->nullable(); // Additional metadata
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_sections');
    }
};
