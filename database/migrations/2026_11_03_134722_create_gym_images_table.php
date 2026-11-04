<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_images', function (Blueprint $table) {
            $table->id('image_id');

            $table->foreignId('gym_id')
                ->constrained('gyms', 'gym_id')
                ->cascadeOnDelete();

            $table->string('image_path');

            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_images');
    }
};
