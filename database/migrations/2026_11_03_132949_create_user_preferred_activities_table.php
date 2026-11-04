<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferred_activities', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('activity_id')
                ->constrained('activities', 'activity_id')
                ->cascadeOnDelete();

            $table->primary(['user_id', 'activity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferred_activities');
    }
};
