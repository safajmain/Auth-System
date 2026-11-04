 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_working_hours', function (Blueprint $table) {
            $table->id('working_hour_id');

            $table->foreignId('gym_id')
                ->constrained('gyms', 'gym_id')
                ->cascadeOnDelete();

            $table->string('day_of_week', 20);

            $table->time('open_time');

            $table->time('close_time');
       
       
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_working_hours');
    }
};
