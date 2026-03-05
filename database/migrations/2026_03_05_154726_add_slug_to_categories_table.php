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
        // Add slug column if it doesn't exist
        if (!Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        // Generate slugs for existing categories
        $categories = \App\Models\Category::all();
        foreach ($categories as $category) {
            if (empty($category->slug)) {
                $slug = \Illuminate\Support\Str::slug($category->title);
                $originalSlug = $slug;
                $counter = 1;

                while (\App\Models\Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $category->slug = $slug;
                $category->save();
            }
        }

        // Now add unique constraint
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
