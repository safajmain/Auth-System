
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_memberships', function (Blueprint $table) {
            $table->id('membership_id');

            $table->foreignId('gym_id')
                ->constrained('gyms', 'gym_id')
                ->cascadeOnDelete();

            $table->unsignedInteger('duration_months');

            $table->decimal('price_jod', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_memberships');
    }
};
