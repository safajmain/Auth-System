<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_activities', function (Blueprint $table) {
            $table->foreignId('gym_id')
                ->constrained('gyms', 'gym_id')
                ->cascadeOnDelete();

            $table->foreignId('activity_id')
                ->constrained('activities', 'activity_id')
                ->cascadeOnDelete();

            $table->primary(['gym_id', 'activity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_activities');
    }
};
