<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymReview extends Model
{
    protected $table = 'gym_reviews';

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'user_id',
        'gym_id',
        'rating',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(
            Gym::class,
            'gym_id',
            'gym_id'
        );
    }

    public function getRouteKeyName(): string
    {
        return 'review_id';
    }
}
