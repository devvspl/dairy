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
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('mission_title')->nullable()->after('usps');
            $table->string('mission_icon')->nullable()->after('mission_title');
            $table->text('mission_description')->nullable()->after('mission_icon');
            $table->string('vision_title')->nullable()->after('mission_description');
            $table->string('vision_icon')->nullable()->after('vision_title');
            $table->text('vision_description')->nullable()->after('vision_icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn([
                'mission_title',
                'mission_icon',
                'mission_description',
                'vision_title',
                'vision_icon',
                'vision_description',
            ]);
        });
    }
};
