
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_schedules', function (Blueprint $table) {
            $table->id('schedule_id');

            $table->foreignId('notification_id')
                ->constrained('notifications', 'notification_id')
                ->cascadeOnDelete();

            $table->enum('day_of_week', [
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            ])->nullable();

            $table->time('reminder_time');

            $table->dateTime('last_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_schedules');
    }
};
