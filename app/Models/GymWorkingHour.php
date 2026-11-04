<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymWorkingHour extends Model
{
    protected $table = 'gym_working_hours';

    protected $primaryKey = 'working_hour_id';

    public $timestamps = false;

    protected $fillable = [
        'gym_id',
        'day_of_week',
        'open_time',
        'close_time',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(
            Gym::class,
            'gym_id',
            'gym_id'
        );
    }
}
