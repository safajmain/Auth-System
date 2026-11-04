<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class UserAvailableTime extends Model
    {
        protected $table = 'user_available_times';

        protected $primaryKey = 'available_time_id';

        public $timestamps = false;

        protected $fillable = [
            'user_id',
            'day_of_week',
            'start_time',
            'end_time',
        ];

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id', 'id');
        }
    }
