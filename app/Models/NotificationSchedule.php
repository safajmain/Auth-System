<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSchedule extends Model
{
    protected $table = 'notification_schedules';

    protected $primaryKey = 'schedule_id';

    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'day_of_week',
        'reminder_time',
        'last_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'last_sent_at' => 'datetime',
        ];
    }

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(
            Reminder::class,
            'notification_id',
            'notification_id'
        );
    }
}
