<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('category')->nullable()->after('ticket_number');
        });
    }

    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
