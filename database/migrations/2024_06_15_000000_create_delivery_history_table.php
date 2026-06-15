<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_log_id')->constrained()->onDelete('cascade');
            $table->string('action_type'); // 'status_change', 'quantity_change', 'person_change', 'note_added', etc.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('description');
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('changed_at');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['delivery_log_id', 'changed_at']);
            $table->index(['action_type', 'changed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_history');
    }
};